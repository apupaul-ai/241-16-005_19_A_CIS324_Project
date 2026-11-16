<?php
class TripController
{
    private Trip $trips;

    public function __construct(PDO $pdo)
    {
        $this->trips = new Trip($pdo);
    }

    private function requireLogin(): int
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /pothik/login.php');
            exit;
        }
        return (int) $_SESSION['user_id'];
    }

    private function isValidDate(string $d): bool
    {
        if ($d === '') {
            return true;
        }
        $dt = DateTime::createFromFormat('Y-m-d', $d);
        return $dt && $dt->format('Y-m-d') === $d;
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
        $title = '';
        $start = '';
        $end = '';
        $budget = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $start = trim($_POST['start_date'] ?? '');
            $end = trim($_POST['end_date'] ?? '');
            $budget = trim($_POST['budget'] ?? '');

            if ($title === '' || mb_strlen($title) > 150) {
                $errors[] = 'Title is required (max 150 characters).';
            }
            if (!$this->isValidDate($start) || !$this->isValidDate($end)) {
                $errors[] = 'Enter valid dates.';
            } elseif ($start !== '' && $end !== '' && $end < $start) {
                $errors[] = 'End date cannot be before start date.';
            }
            if ($budget !== '' && (!is_numeric($budget) || (float) $budget < 0)) {
                $errors[] = 'Budget must be a positive number.';
            }

            if (!$errors) {
                $this->trips->create(
                    $userId,
                    $title,
                    $start === '' ? null : $start,
                    $end === '' ? null : $end,
                    $budget === '' ? null : (float) $budget
                );
                header('Location: /pothik/trips.php');
                exit;
            }
        }

        $pageTitle = 'New trip';
        require __DIR__ . '/../views/trips/form.php';
    }
}