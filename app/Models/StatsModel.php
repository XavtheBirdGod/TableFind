<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * StatsModel
 * Verantwoordelijk voor het ophalen van statistische gegevens uit de database.
 */
class StatsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Haalt het totaal aantal records op uit een specifieke tabel.
     * Controleert automatisch of 'deleted_at' bestaat.
     */
    public function getTotalCount(string $table, bool $softDelete = true): int
    {
        $sql = "SELECT COUNT(*) FROM `$table`";

        if ($softDelete && $this->columnExists($table, 'deleted_at')) {
            $sql .= " WHERE deleted_at IS NULL";
        }

        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Haalt het aantal reserveringen op voor vandaag.
     */
    public function getTodaysReservationsCount(): int
    {
        // تحقق آمن من وجود deleted_at
        if ($this->columnExists('reservations', 'deleted_at')) {
            $sql = "SELECT COUNT(*) 
                    FROM reservations 
                    WHERE reservation_date = CURDATE()
                    AND deleted_at IS NULL";
        } else {
            $sql = "SELECT COUNT(*) 
                    FROM reservations 
                    WHERE reservation_date = CURDATE()";
        }

        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Haalt het aantal beschikbare tafels op.
     */
    public function getAvailableTablesCount(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM tables WHERE status = 'available'"
        );
        return (int) $stmt->fetchColumn();
    }

    /**
     * Controleert of een kolom bestaat in een tabel.
     */
    private function columnExists(string $table, string $column): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = :table
              AND COLUMN_NAME = :column
        ");

        $stmt->execute([
            'table'  => $table,
            'column' => $column
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
