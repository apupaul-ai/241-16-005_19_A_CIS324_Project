<?php
require_once __DIR__ . '/../models/Destination.php';
require_once __DIR__ . '/../models/DirectoryItem.php';
require_once __DIR__ . '/../models/EmergencyContact.php';
require_once __DIR__ . '/../models/PackingItem.php';

class InfoController
{
    private Destination $destinations;
    private DirectoryItem $directory;
    private EmergencyContact $emergency;
    private PackingItem $packing;

    public function __construct(PDO $pdo)
    {
        $this->destinations = new Destination($pdo);
        $this->directory = new DirectoryItem($pdo);
        $this->emergency = new EmergencyContact($pdo);
        $this->packing = new PackingItem($pdo);
    }

    public function directory(): void
    {
        $destinationId = (int) ($_GET['destination'] ?? 0);
        $type = $_GET['type'] ?? '';
        if (!in_array($type, ['hotel', 'restaurant', 'transport'], true)) {
            $type = '';
        }

        $items = $this->directory->search($destinationId, $type);
        $options = $this->destinations->all();

        $pageTitle = 'Travel directory';
        require __DIR__ . '/../views/info/directory.php';
    }

    public function emergency(): void
    {
        $national = [];
        $local = [];
        foreach ($this->emergency->all() as $c) {
            if ($c['destination'] === null) {
                $national[] = $c;
            } else {
                $local[$c['destination']][] = $c;
            }
        }

        $pageTitle = 'Emergency and essentials';
        require __DIR__ . '/../views/info/emergency.php';
    }

    public function budget(): void
    {
        $options = $this->destinations->all();
        $errors = [];
        $result = null;
        $values = ['destination' => 0, 'days' => 3, 'travelers' => 1, 'transport' => '', 'extra' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $values['destination'] = (int) ($_POST['destination'] ?? 0);
            $values['days'] = (int) ($_POST['days'] ?? 0);
            $values['travelers'] = (int) ($_POST['travelers'] ?? 0);
            $values['transport'] = trim($_POST['transport'] ?? '');
            $values['extra'] = trim($_POST['extra'] ?? '');

            $destination = $this->destinations->find($values['destination']);

            if ($destination === null) {
                $errors[] = 'Choose a destination.';
            }
            if ($values['days'] < 1 || $values['days'] > 30) {
                $errors[] = 'Days must be between 1 and 30.';
            }
            if ($values['travelers'] < 1 || $values['travelers'] > 20) {
                $errors[] = 'Travelers must be between 1 and 20.';
            }
            foreach (['transport' => 'Transport cost', 'extra' => 'Extra expenses'] as $key => $label) {
                if ($values[$key] !== '' && (!is_numeric($values[$key]) || (float) $values[$key] < 0)) {
                    $errors[] = $label . ' must be a positive number.';
                }
            }

            if (!$errors) {
                $daily = (float) $destination['avg_daily_cost'];
                $stay = $daily * $values['days'] * $values['travelers'];
                $transport = $values['transport'] === '' ? 0.0 : (float) $values['transport'];
                $extra = $values['extra'] === '' ? 0.0 : (float) $values['extra'];
                $total = $stay + $transport + $extra;

                $result = [
                    'destination' => $destination['name'],
                    'daily'       => $daily,
                    'stay'        => $stay,
                    'transport'   => $transport,
                    'extra'       => $extra,
                    'total'       => $total,
                    'perPerson'   => $total / $values['travelers'],
                ];
            }
        }

        $pageTitle = 'Budget calculator';
        require __DIR__ . '/../views/info/budget.php';
    }

    public function packing(): void
    {
        $grouped = [];
        foreach ($this->packing->all() as $item) {
            $grouped[$item['category'] ?? 'Other'][] = $item;
        }

        $pageTitle = 'Packing checklist';
        require __DIR__ . '/../views/info/packing.php';
    }
}