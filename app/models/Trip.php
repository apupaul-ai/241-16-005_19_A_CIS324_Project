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

    public function find(int $id, int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, title, start_date, end_date, budget
             FROM trips WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$id, $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(int $userId, string $title, ?string $start, ?string $end, ?float $budget): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO trips (user_id, title, start_date, end_date, budget) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$userId, $title, $start, $end, $budget]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, int $userId, string $title, ?string $start, ?string $end, ?float $budget): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE trips SET title = ?, start_date = ?, end_date = ?, budget = ?
             WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$title, $start, $end, $budget, $id, $userId]);
    }

    public function delete(int $id, int $userId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM trips WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
    }

    public function stops(int $tripId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT td.id, td.day_number, td.notes, d.id AS destination_id, d.name, d.avg_daily_cost
             FROM trip_destinations td
             JOIN destinations d ON td.destination_id = d.id
             WHERE td.trip_id = ?
             ORDER BY td.day_number, td.id"
        );
        $stmt->execute([$tripId]);
        return $stmt->fetchAll();
    }

    public function addStop(int $tripId, int $destinationId, int $day, string $notes): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO trip_destinations (trip_id, destination_id, day_number, notes) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$tripId, $destinationId, $day, $notes]);
    }

    public function removeStop(int $stopId, int $tripId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM trip_destinations WHERE id = ? AND trip_id = ?");
        $stmt->execute([$stopId, $tripId]);
    }
}