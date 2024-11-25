<?php

namespace App\Services;

use PDO;
use App\Database;

class ContractService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getContracts(array $filters, string $sortColumn = 'id', string $sortOrder = 'asc'): array
    {
        $allowedColumns = ['id', 'entrepreneur_name', 'nip', 'amount'];
        $sortColumn = in_array($sortColumn, $allowedColumns, true) ? $sortColumn : 'id';
        $sortOrder = $sortOrder === 'desc' ? 'DESC' : 'ASC';

        $sql = "SELECT id, entrepreneur_name, nip, amount FROM contracts WHERE 1=1";

        if (!empty($filters['amount_min'])) {
            $sql .= " AND amount > :amount_min";
        }

        $sql .= " ORDER BY {$sortColumn} {$sortOrder}";

        $stmt = $this->pdo->prepare($sql);

        if (!empty($filters['amount_min'])) {
            $stmt->bindValue(':amount_min', $filters['amount_min'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}