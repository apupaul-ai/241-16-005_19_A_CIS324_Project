<?php
class TransportMode
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        return $this->pdo->query(
            "SELECT id, name, cost_per_km, note FROM transport_modes ORDER BY id"
        )->fetchAll();
    }
}