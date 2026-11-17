<?php
class PackingItem
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        return $this->pdo->query(
            "SELECT id, item_name, category FROM packing_items ORDER BY category, item_name"
        )->fetchAll();
    }
}