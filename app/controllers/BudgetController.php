<?php
require_once __DIR__ . '/../models/City.php';
require_once __DIR__ . '/../models/TransportMode.php';
require_once __DIR__ . '/../services/CostCalculator.php';

class BudgetController
{
    private PDO $pdo;
    private City $cities;
    private TransportMode $modes;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->cities = new City($pdo);
        $this->modes = new TransportMode($pdo);
    }

    public function index(): void
    {
        $cityOptions = $this->cities->all();
        $modes = $this->modes->all();
        $styles = CostCalculator::STYLES;

        // Cheapest transport mode is the default choice.
        $defaultMode = 0;
        $lowest = PHP_FLOAT_MAX;
        foreach ($modes as $m) {
            if ((float) $m['cost_per_km'] < $lowest) {
                $lowest = (float) $m['cost_per_km'];
                $defaultMode = (int) $m['id'];
            }
        }

        $values = [
            'budget' => 0, 'from' => 0, 'travelers' => 1, 'days' => 3,
            'mode' => $defaultMode, 'style' => 'standard', 'peak' => false,
        ];
        $errors = [];
        $plans = null;
        $summary = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $values['budget'] = (int) ($_POST['budget'] ?? 0);
            $values['from'] = (int) ($_POST['from'] ?? 0);
            $values['travelers'] = (int) ($_POST['travelers'] ?? 0);
            $values['days'] = (int) ($_POST['days'] ?? 0);
            $values['mode'] = (int) ($_POST['mode'] ?? 0);
            $values['style'] = (string) ($_POST['style'] ?? 'standard');
            $values['peak'] = isset($_POST['peak']);

            $city = $this->cities->find($values['from']);
            $chosen = null;
            foreach ($modes as $m) {
                if ((int) $m['id'] === $values['mode']) {
                    $chosen = $m;
                    break;
                }
            }

            if ($values['budget'] < 1000 || $values['budget'] > 10000000) {
                $errors[] = 'Budget must be between BDT 1,000 and BDT 10,000,000.';
            }
            if ($city === null) {
                $errors[] = 'Choose where you are starting from.';
            }
            if ($chosen === null) {
                $errors[] = 'Choose a transport mode.';
            }
            if (!array_key_exists($values['style'], $styles)) {
                $errors[] = 'Choose a travel style.';
            }
            if ($values['days'] < 1 || $values['days'] > 30) {
                $errors[] = 'Days must be between 1 and 30.';
            }
            if ($values['travelers'] < 1 || $values['travelers'] > 20) {
                $errors[] = 'Travelers must be between 1 and 20.';
            }

            if (!$errors) {
                $rows = $this->pdo->query(
                    'SELECT d.id, d.name, d.division, d.image, d.avg_daily_cost, d.latitude, d.longitude,
                            d.transfer_cost, c.name AS category
                     FROM destinations d JOIN categories c ON c.id = d.category_id
                     WHERE d.latitude IS NOT NULL AND d.longitude IS NOT NULL'
                )->fetchAll();

                $perKm = (float) $chosen['cost_per_km'];
                $plans = [];

                foreach ($rows as $d) {
                    $roadKm = CostCalculator::roadKm(
                        (float) $city['latitude'], (float) $city['longitude'],
                        (float) $d['latitude'], (float) $d['longitude']
                    );
                    $transport = round($roadKm * $perKm * 2 * $values['travelers']);
                    $transfer = round((float) $d['transfer_cost'] * $values['travelers']);
                    $ground = CostCalculator::onTheGround(
                        (float) $d['avg_daily_cost'], $values['days'],
                        $values['travelers'], $values['style'], $values['peak']
                    );

                    $subtotal = $transport + $transfer + $ground['hotel'] + $ground['food'] + $ground['local'];
                    $margins = CostCalculator::margins($subtotal);
                    $total = $subtotal + $margins['extras'] + $margins['buffer'];
                    $left = $values['budget'] - $total;

                    if ($left >= $values['budget'] * 0.10) {
                        $status = 'comfortable';
                    } elseif ($left >= 0) {
                        $status = 'tight';
                    } else {
                        $status = 'over';
                    }

                    $plans[] = [
                        'id'        => (int) $d['id'],
                        'name'      => $d['name'],
                        'division'  => $d['division'],
                        'category'  => $d['category'],
                        'image'     => $d['image'],
                        'roadKm'    => round($roadKm),
                        'transport' => $transport + $transfer,
                        'stay'      => $ground['hotel'] + $ground['food'] + $ground['local'],
                        'total'     => $total,
                        'left'      => $left,
                        'status'    => $status,
                    ];
                }

                usort($plans, fn($a, $b) => $a['total'] <=> $b['total']);

                $summary = [
                    'fit'      => count(array_filter($plans, fn($p) => $p['status'] !== 'over')),
                    'total'    => count($plans),
                    'cheapest' => $plans[0] ?? null,
                ];
            }
        }

        $pageTitle = 'Budget planner';
        require __DIR__ . '/../views/info/budget.php';
    }
}