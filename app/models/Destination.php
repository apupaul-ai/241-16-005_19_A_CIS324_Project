<?php
class Destination
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        return $this->pdo->query("SELECT id, name FROM destinations ORDER BY name")->fetchAll();
    }

    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM destinations WHERE id = ?");
        $stmt->execute([$id]);
        return (bool) $stmt->fetch();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, name, avg_daily_cost FROM destinations WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}