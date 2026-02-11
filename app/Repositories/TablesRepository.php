<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * TablesRepository
 * Beheert alle database-interacties voor de tafels.
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

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM tables ORDER BY table_number ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        return $this->all();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tables WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Slaat een nieuwe tafel op in de database.
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO tables (table_number, capacity, status) VALUES (:table_number, :capacity, :status)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'table_number' => $data['table_number'],
            'capacity'     => $data['capacity'],
            'status'       => $data['status'] ?? 'available'
        ]);
    }

    /**
     * Werkt een bestaande tafel bij.
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE tables SET table_number = :table_number, capacity = :capacity, status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'table_number' => $data['table_number'],
            'capacity'     => $data['capacity'],
            'status'       => $data['status'],
            'id'           => $id
        ]);
    }

    /**
     * Verwijdert een tafel.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM tables WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}