<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * UsersRepository
 * Beheert de database-interacties voor gebruikers en hun toegangsrechten.
 */
final class UsersRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Statische factory methode voor consistente initialisatie.
     */
    public static function make(): self
    {
        return new self(Database::getConnection());
    }

    /**
     * Zoekt een actieve gebruiker op basis van e-mailadres voor authenticatie.
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email AND u.is_active = 1 
                LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Haalt alle gebruikers op voor het administratie-overzicht.
     */
    public function getAll(): array
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                ORDER BY u.created_at DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}