<?php
class Trip
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function allByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, title, start_date, end_date, budget
             FROM trips WHERE user_id = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function create(int $userId, string $title, ?string $start, ?string $end, ?float $budget): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO trips (user_id, title, start_date, end_date, budget) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$userId, $title, $start, $end, $budget]);
        return (int) $this->pdo->lastInsertId();
    }
}