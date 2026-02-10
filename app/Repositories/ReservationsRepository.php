<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;
use Exception;

/**
 * ReservationsRepository
 * Centraliseert alle database-operaties voor reserveringen met robuuste foutafhandeling.
 */
final class ReservationsRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Factory methode om een instantie te maken met een actieve databaseverbinding.
     */
    public static function make(): self
    {
        return new self(Database::getConnection());
    }

    /**
     * Haalt alle actieve reserveringen op inclusief tafeldetails.
     */
    public function getAll(): array
    {
        try {
            $sql = "SELECT r.*, t.table_number 
                    FROM reservations r 
                    LEFT JOIN tables t ON r.table_id = t.id 
                    WHERE r.deleted_at IS NULL 
                    ORDER BY r.reservation_date DESC, r.reservation_time DESC";
            
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Exception $e) {
            error_log("Fout in ReservationsRepository::getAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Maakt een nieuwe reservering aan.
     */
    public function create(array $data): bool
    {
        try {
            $sql = "INSERT INTO reservations 
                    (customer_name, customer_email, customer_phone, guest_count, reservation_date, reservation_time, table_id, status, notes, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            return $this->pdo->prepare($sql)->execute([
                $data['customer_name'],
                $data['customer_email'] ?? null,
                $data['customer_phone'],
                $data['guest_count'],
                $data['reservation_date'],
                $data['reservation_time'],
                $data['table_id'] ?? null,
                $data['status'] ?? 'pending',
                $data['notes'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Fout bij aanmaken reservering: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Zoekt een specifieke reservering op ID.
     */
    public function find(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM reservations WHERE id = :id AND deleted_at IS NULL LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            error_log("Fout bij zoeken reservering ID $id: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Werkt de gegevens van een bestaande reservering bij.
     */
    public function update(int $id, array $data): bool
    {
        try {
            $sql = "UPDATE reservations SET 
                    customer_name = ?, 
                    customer_email = ?, 
                    customer_phone = ?, 
                    guest_count = ?, 
                    reservation_date = ?, 
                    reservation_time = ?, 
                    table_id = ?, 
                    status = ?, 
                    notes = ?, 
                    updated_at = NOW() 
                    WHERE id = ?";
            
            return $this->pdo->prepare($sql)->execute([
                $data['customer_name'],
                $data['customer_email'],
                $data['customer_phone'],
                $data['guest_count'],
                $data['reservation_date'],
                $data['reservation_time'],
                $data['table_id'],
                $data['status'],
                $data['notes'],
                $id
            ]);
        } catch (Exception $e) {
            error_log("Fout bij bijwerken reservering ID $id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Voert een soft-delete uit op een reservering.
     */
    public function delete(int $id): bool
    {
        try {
            $sql = "UPDATE reservations SET deleted_at = NOW() WHERE id = ?";
            return $this->pdo->prepare($sql)->execute([$id]);
        } catch (Exception $e) {
            error_log("Fout bij verwijderen reservering ID $id: " . $e->getMessage());
            return false;
        }
    }
}