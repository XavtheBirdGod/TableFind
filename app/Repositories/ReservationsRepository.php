<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * ReservationsRepository
 * Beheert alle database-interacties voor de 'reservations' tabel.
 */
final class ReservationsRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function make(): self
    {
        return new self(Database::getConnection());
    }

    /**
     * Haalt alle reserveringen op met bijbehorende tafelnummers.
     */
    public function getAll(): array
    {
        $sql = "SELECT r.*, t.table_number 
                FROM reservations r 
                LEFT JOIN tables t ON r.table_id = t.id 
                WHERE r.deleted_at IS NULL 
                ORDER BY r.reservation_date DESC, r.reservation_time DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Maakt een nieuwe reservering aan.
     */
    public function create(array $data): bool
    {
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
    }

    /**
     * Zoekt één reservering op basis van ID.
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM reservations WHERE id = :id AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Markeert een reservering als verwijderd (soft delete).
     */
    public function delete(int $id): bool
    {
        $sql = "UPDATE reservations SET deleted_at = NOW() WHERE id = ?";
        return $this->pdo->prepare($sql)->execute([$id]);
    }
}