<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * TablesRepository
 * Beheert de database-operaties voor de restauranttafels.
 */
final class TablesRepository
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

    public function getAll(): array
    {
        $sql = "SELECT * FROM tables ORDER BY table_number ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM tables WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Voegt een nieuwe tafel toe aan de database.
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO tables (table_number, capacity, status) VALUES (?, ?, ?)";
        return $this->pdo->prepare($sql)->execute([
            $data['table_number'],
            $data['capacity'],
            $data['status']
        ]);
    }

    /**
     * Werkt een bestaande tafel bij.
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE tables SET table_number = ?, capacity = ?, status = ?, updated_at = NOW() WHERE id = ?";
        return $this->pdo->prepare($sql)->execute([
            $data['table_number'],
            $data['capacity'],
            $data['status'],
            $id
        ]);
    }

    /**
     * Verwijdert een tafel op basis van ID.
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tables WHERE id = ?";
        return $this->pdo->prepare($sql)->execute([$id]);
    }
}