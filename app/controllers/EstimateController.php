<?php
require_once __DIR__ . '/../models/City.php';
require_once __DIR__ . '/../models/Destination.php';
require_once __DIR__ . '/../models/TransportMode.php';

class EstimateController
{
    // Roads are longer than a straight line, so we scale the distance up.
    private const ROAD_FACTOR = 1.3;
    private const EXTRAS_SHARE = 0.10;
    private const BUFFER_SHARE = 0.10;

    private City $cities;
    private Destination $destinations;
    private TransportMode $modes;

    public function __construct(PDO $pdo)
    {
        $this->cities = new City($pdo);
        $this->destinations = new Destination($pdo);
        $this->modes = new TransportMode($pdo);
    }

    private function distanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $r = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return 2 * $r * asin(sqrt($a));
    }

    public function index(): void
    {
        $cityOptions = $this->cities->all();
        $destinationOptions = $this->destinations->all();
        $modes = $this->modes->all();

        $values = ['from' => 0, 'to' => 0, 'travelers' => 1, 'days' => 3, 'mode' => 0];
        $errors = [];
        $result = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $values['from'] = (int) ($_POST['from'] ?? 0);
            $values['to'] = (int) ($_POST['to'] ?? 0);
            $values['travelers'] = (int) ($_POST['travelers'] ?? 0);
            $values['days'] = (int) ($_POST['days'] ?? 0);
            $values['mode'] = (int) ($_POST['mode'] ?? 0);

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
            if ($values['days'] < 1 || $values['days'] > 30) {
                $errors[] = 'Days must be between 1 and 30.';
            }
            if ($values['travelers'] < 1 || $values['travelers'] > 20) {
                $errors[] = 'Travelers must be between 1 and 20.';
            }

            if (!$errors) {
                $straightKm = $this->distanceKm(
                    (float) $city['latitude'],
                    (float) $city['longitude'],
                    (float) $destination['latitude'],
                    (float) $destination['longitude']
                );
                $roadKm = max(20.0, $straightKm * self::ROAD_FACTOR);

                $options = [];
                $selected = null;
                foreach ($modes as $m) {
                    $oneWay = $roadKm * (float) $m['cost_per_km'];
                    $option = [
                        'id'        => (int) $m['id'],
                        'name'      => $m['name'],
                        'note'      => $m['note'],
                        'oneWay'    => $oneWay,
                        'roundTrip' => $oneWay * 2 * $values['travelers'],
                    ];
                    $options[] = $option;
                    if ($option['id'] === $values['mode']) {
                        $selected = $option;
                    }
                }

                $transport = round($selected['roundTrip']);
                $stay = round((float) $destination['avg_daily_cost'] * $values['days'] * $values['travelers']);
                $subtotal = $transport + $stay;
                $extras = round($subtotal * self::EXTRAS_SHARE);
                $buffer = round($subtotal * self::BUFFER_SHARE);

                $result = [
                    'from'      => $city['name'],
                    'to'        => $destination['name'],
                    'straightKm'=> $straightKm,
                    'roadKm'    => $roadKm,
                    'options'   => $options,
                    'selected'  => $selected['name'],
                    'travelers' => $values['travelers'],
                    'days'      => $values['days'],
                    'transport' => $transport,
                    'stay'      => $stay,
                    'extras'    => $extras,
                    'buffer'    => $buffer,
                ];
            }
        }

        $pageTitle = 'Trip cost estimator';
        require __DIR__ . '/../views/info/estimate.php';
    }
}