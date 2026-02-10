<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class StatsRepository
{
    private PDO $db;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }
    
    public static function make(): self
    {
        return new self(Database::getConnection());
    }

    public function getTotalCount(string $table): int
    {
        // Whitelist allowed tables to prevent SQL injection
        $allowed = ['users', 'tables', 'reservations'];
        if (!in_array($table, $allowed)) {
            return 0;
        }

        $sql = "SELECT COUNT(*) FROM `$table`";
        
        // Simple check for soft delete column existence is tricky without SQL inside Models
        // but for this project we know reservations has soft delete.
        if ($table === 'reservations') {
            $sql .= " WHERE deleted_at IS NULL";
        }

        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getTodaysReservationsCount(): int
    {
        $sql = "SELECT COUNT(*) FROM reservations WHERE reservation_date = CURDATE() AND deleted_at IS NULL";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getAvailableTablesCount(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM tables WHERE status = 'available'");
        return (int) $stmt->fetchColumn();
    }
}
