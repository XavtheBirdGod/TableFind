<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * UsersRepository
 * Beheert alle database-interacties voor gebruikersgegevens.
 */
final class UsersRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Statische factory methode voor directe initialisatie.
     */
    public static function make(): self
    {
        return new self(Database::getConnection());
    }

    /**
     * Zoekt een gebruiker op basis van hun unieke ID.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Zoekt een gebruiker op basis van hun e-mailadres.
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Werkt het profiel van de gebruiker bij in de database.
     * Als passwordHash null is, wordt het wachtwoord niet gewijzigd.
     */
    public function updateProfile(int $id, string $email, string $name, ?string $passwordHash = null): bool
    {
        if ($passwordHash) {
            $sql = "UPDATE users SET email = :email, name = :name, password_hash = :password_hash WHERE id = :id";
            $params = [
                'email' => $email,
                'name' => $name,
                'password_hash' => $passwordHash,
                'id' => $id
            ];
        } else {
            $sql = "UPDATE users SET email = :email, name = :name WHERE id = :id";
            $params = [
                'email' => $email,
                'name' => $name,
                'id' => $id
            ];
        }

        try {
            return $this->pdo->prepare($sql)->execute($params);
        } catch (\PDOException $e) {
            // Log de fout indien nodig
            return false;
        }
    }
}