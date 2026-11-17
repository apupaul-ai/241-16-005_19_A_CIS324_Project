<?php
class EmergencyContact
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        return $this->pdo->query(
            "SELECT ec.type, ec.name, ec.phone, d.name AS destination
             FROM emergency_contacts ec
             LEFT JOIN destinations d ON ec.destination_id = d.id
             ORDER BY d.name, ec.type, ec.name"
        )->fetchAll();
    }
}