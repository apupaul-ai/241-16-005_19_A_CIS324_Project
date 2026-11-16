<?php
class TripController
{
    private Trip $trips;
    private Destination $destinations;

    public function __construct(PDO $pdo)
    {
        $this->trips = new Trip($pdo);
        $this->destinations = new Destination($pdo);
    }

    private function requireLogin(): int
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /pothik/login.php');
            exit;
        }
        return (int) $_SESSION['user_id'];
    }

    private function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pothik/trips.php');
            exit;
        }
    }

    private function isValidDate(string $d): bool
    {
        if ($d === '') {
            return true;
        }
        $dt = DateTime::createFromFormat('Y-m-d', $d);
        return $dt && $dt->format('Y-m-d') === $d;
    }

    private function validate(array $input): array
    {
        $values = [
            'title'  => trim($input['title'] ?? ''),
            'start'  => trim($input['start_date'] ?? ''),
            'end'    => trim($input['end_date'] ?? ''),
            'budget' => trim($input['budget'] ?? ''),
        ];
        $errors = [];

        if ($values['title'] === '' || mb_strlen($values['title']) > 150) {
            $errors[] = 'Title is required (max 150 characters).';
        }
        if (!$this->isValidDate($values['start']) || !$this->isValidDate($values['end'])) {
            $errors[] = 'Enter valid dates.';
        } elseif ($values['start'] !== '' && $values['end'] !== '' && $values['end'] < $values['start']) {
            $errors[] = 'End date cannot be before start date.';
        }
        if ($values['budget'] !== '' && (!is_numeric($values['budget']) || (float) $values['budget'] < 0)) {
            $errors[] = 'Budget must be a positive number.';
        }

        return [$errors, $values];
    }

    private function toDb(array $v): array
    {
        return [
            $v['title'],
            $v['start'] === '' ? null : $v['start'],
            $v['end'] === '' ? null : $v['end'],
            $v['budget'] === '' ? null : (float) $v['budget'],
        ];
    }

    private function findOrFail(int $userId): array
    {
        $trip = $this->trips->find((int) ($_GET['id'] ?? 0), $userId);
        if ($trip === null) {
            http_response_code(404);
            $pageTitle = 'Not found';
            require __DIR__ . '/../../includes/header.php';
            echo '<section class="max-w-6xl mx-auto px-4 py-10"><p>Trip not found.</p></section>';
            require __DIR__ . '/../../includes/footer.php';
            exit;
        }
        return $trip;
    }

    public function index(): void
    {
        $userId = $this->requireLogin();
        $trips = $this->trips->allByUser($userId);
        $pageTitle = 'My trips';
        require __DIR__ . '/../views/trips/index.php';
    }

    public function create(): void
    {
        $userId = $this->requireLogin();
        $errors = [];
        $values = ['title' => '', 'start' => '', 'end' => '', 'budget' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$errors, $values] = $this->validate($_POST);
            if (!$errors) {
                $id = $this->trips->create($userId, ...$this->toDb($values));
                header('Location: /pothik/trips.php?action=show&id=' . $id);
                exit;
            }
        }

        $pageTitle = 'New trip';
        $formTitle = 'New trip';
        $formAction = '/pothik/trips.php?action=create';
        require __DIR__ . '/../views/trips/form.php';
    }

    public function edit(): void
    {
        $userId = $this->requireLogin();
        $trip = $this->findOrFail($userId);

        $errors = [];
        $values = [
            'title'  => $trip['title'],
            'start'  => $trip['start_date'] ?? '',
            'end'    => $trip['end_date'] ?? '',
            'budget' => $trip['budget'] ?? '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$errors, $values] = $this->validate($_POST);
            if (!$errors) {
                $this->trips->update($trip['id'], $userId, ...$this->toDb($values));
                header('Location: /pothik/trips.php?action=show&id=' . $trip['id']);
                exit;
            }
        }

        $pageTitle = 'Edit trip';
        $formTitle = 'Edit trip';
        $formAction = '/pothik/trips.php?action=edit&id=' . (int) $trip['id'];
        require __DIR__ . '/../views/trips/form.php';
    }

    public function delete(): void
    {
        $userId = $this->requireLogin();
        $this->requirePost();
        $trip = $this->findOrFail($userId);
        $this->trips->delete($trip['id'], $userId);
        header('Location: /pothik/trips.php');
        exit;
    }

    public function show(): void
    {
        $userId = $this->requireLogin();
        $trip = $this->findOrFail($userId);
        $stops = $this->trips->stops($trip['id']);
        $options = $this->destinations->all();

        $estimated = array_sum(array_map(fn($s) => (float) $s['avg_daily_cost'], $stops));

        $stopErrors = $_SESSION['stop_errors'] ?? [];
        unset($_SESSION['stop_errors']);

        $pageTitle = $trip['title'];
        require __DIR__ . '/../views/trips/show.php';
    }

    public function addStop(): void
    {
        $userId = $this->requireLogin();
        $this->requirePost();
        $trip = $this->findOrFail($userId);

        $destinationId = (int) ($_POST['destination_id'] ?? 0);
        $day = (int) ($_POST['day_number'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');
        $errors = [];

        if (!$this->destinations->exists($destinationId)) {
            $errors[] = 'Choose a destination.';
        }
        if ($day < 1 || $day > 60) {
            $errors[] = 'Day must be between 1 and 60.';
        }
        if (mb_strlen($notes) > 500) {
            $errors[] = 'Notes must be 500 characters or less.';
        }

        if ($errors) {
            $_SESSION['stop_errors'] = $errors;
        } else {
            $this->trips->addStop($trip['id'], $destinationId, $day, $notes);
        }

        header('Location: /pothik/trips.php?action=show&id=' . $trip['id']);
        exit;
    }

    public function removeStop(): void
    {
        $userId = $this->requireLogin();
        $this->requirePost();
        $trip = $this->findOrFail($userId);
        $this->trips->removeStop((int) ($_POST['stop_id'] ?? 0), $trip['id']);
        header('Location: /pothik/trips.php?action=show&id=' . $trip['id']);
        exit;
    }
}