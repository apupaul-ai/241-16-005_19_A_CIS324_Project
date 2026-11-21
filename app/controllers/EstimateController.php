<?php
require_once __DIR__ . '/../models/City.php';
require_once __DIR__ . '/../models/Destination.php';
require_once __DIR__ . '/../models/TransportMode.php';
require_once __DIR__ . '/../services/CostCalculator.php';

class EstimateController
{
    private PDO $pdo;
    private City $cities;
    private Destination $destinations;
    private TransportMode $modes;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->cities = new City($pdo);
        $this->destinations = new Destination($pdo);
        $this->modes = new TransportMode($pdo);
    }

    // Fixed round-trip cost per person (boat, ship, permits) stored in the database.
    private function transferCost(int $destinationId): float
    {
        $stmt = $this->pdo->prepare('SELECT transfer_cost FROM destinations WHERE id = ?');
        $stmt->execute([$destinationId]);
        return (float) ($stmt->fetchColumn() ?: 0);
    }

    public function index(): void
    {
        $cityOptions = $this->cities->all();
        $destinationOptions = $this->destinations->all();
        $modes = $this->modes->all();
        $styles = CostCalculator::STYLES;

        $values = [
            'from' => 0, 'to' => 0, 'travelers' => 1, 'days' => 3,
            'mode' => 0, 'style' => 'standard', 'peak' => false, 'budget' => 0,
        ];
        $errors = [];
        $result = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $values['from'] = (int) ($_POST['from'] ?? 0);
            $values['to'] = (int) ($_POST['to'] ?? 0);
            $values['travelers'] = (int) ($_POST['travelers'] ?? 0);
            $values['days'] = (int) ($_POST['days'] ?? 0);
            $values['mode'] = (int) ($_POST['mode'] ?? 0);
            $values['style'] = (string) ($_POST['style'] ?? 'standard');
            $values['peak'] = isset($_POST['peak']);
            $values['budget'] = max(0, min(10000000, (int) ($_POST['budget'] ?? 0)));

            $city = $this->cities->find($values['from']);
            $destination = $this->destinations->find($values['to']);

            $chosen = null;
            foreach ($modes as $m) {
                if ((int) $m['id'] === $values['mode']) {
                    $chosen = $m;
                    break;
                }
            }

            if ($city === null) {
                $errors[] = 'Choose where you are starting from.';
            }
            if ($destination === null) {
                $errors[] = 'Choose a destination.';
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
                $roadKm = CostCalculator::roadKm(
                    (float) $city['latitude'], (float) $city['longitude'],
                    (float) $destination['latitude'], (float) $destination['longitude']
                );

                // Every transport mode, so the user can compare.
                $options = [];
                $selected = null;
                foreach ($modes as $m) {
                    $oneWay = $roadKm * (float) $m['cost_per_km'];
                    $option = [
                        'id'        => (int) $m['id'],
                        'name'      => $m['name'],
                        'note'      => $m['note'],
                        'perPerson' => round($oneWay * 2),
                        'roundTrip' => round($oneWay * 2 * $values['travelers']),
                    ];
                    $options[] = $option;
                    if ($option['id'] === $values['mode']) {
                        $selected = $option;
                    }
                }
                $cheapest = min(array_column($options, 'roundTrip'));

                $ground = CostCalculator::onTheGround(
                    (float) $destination['avg_daily_cost'],
                    $values['days'], $values['travelers'], $values['style'], $values['peak']
                );
                $transfer = round($this->transferCost((int) $destination['id']) * $values['travelers']);

                $subtotal = $selected['roundTrip'] + $transfer + $ground['hotel'] + $ground['food'] + $ground['local'];
                $margins = CostCalculator::margins($subtotal);

                $items = [
                    ['key' => 'transport', 'label' => 'Transport (round trip)', 'amount' => $selected['roundTrip'],
                     'note' => $selected['name'] . ', ' . round($roadKm) . ' km each way'],
                ];
                if ($transfer > 0) {
                    $items[] = ['key' => 'transfer', 'label' => 'Boat / ship / permits', 'amount' => $transfer,
                                'note' => 'Minimum fixed fare for this destination'];
                }
                $items[] = ['key' => 'hotel', 'label' => 'Hotel', 'amount' => $ground['hotel'],
                            'note' => $ground['rooms'] . ' room(s) x ' . $ground['nights'] . ' night(s)'];
                $items[] = ['key' => 'food', 'label' => 'Food', 'amount' => $ground['food'],
                            'note' => $values['days'] . ' day(s)'];
                $items[] = ['key' => 'local', 'label' => 'Local travel and entry tickets', 'amount' => $ground['local'],
                            'note' => 'Rickshaw, CNG, tickets'];
                $items[] = ['key' => 'extras', 'label' => 'Shopping and extras', 'amount' => $margins['extras'],
                            'note' => '10% of trip cost'];
                $items[] = ['key' => 'buffer', 'label' => 'Emergency buffer', 'amount' => $margins['buffer'],
                            'note' => '10% of trip cost'];

                $result = [
                    'from'      => $city['name'],
                    'to'        => $destination['name'],
                    'straightKm'=> CostCalculator::straightKm($roadKm),
                    'roadKm'    => $roadKm,
                    'options'   => $options,
                    'cheapest'  => $cheapest,
                    'selected'  => $selected['name'],
                    'travelers' => $values['travelers'],
                    'days'      => $values['days'],
                    'style'     => $styles[$values['style']]['label'],
                    'peak'      => $values['peak'],
                    'budget'    => $values['budget'],
                    'items'     => $items,
                ];
            }
        }

        $pageTitle = 'Trip cost estimator';
        require __DIR__ . '/../views/info/estimate.php';
    }
}