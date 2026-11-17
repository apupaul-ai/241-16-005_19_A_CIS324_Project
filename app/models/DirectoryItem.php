<?php
class DirectoryItem
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function search(int $destinationId, string $type): array
    {
        $sql = "SELECT di.type, di.name, di.phone, di.address, di.price_range, d.name AS destination
                FROM directory_items di
                JOIN destinations d ON di.destination_id = d.id
                WHERE 1 = 1";
        $params = [];

        if ($destinationId > 0) {
            $sql .= " AND di.destination_id = ?";
            $params[] = $destinationId;
        }
        if ($type !== '') {
            $sql .= " AND di.type = ?";
            $params[] = $type;
        }

        $sql .= " ORDER BY d.name, di.type, di.name";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}