<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database Class
 * Beheert de PDO-verbinding met de database via het Singleton-patroon.
 */
class Database
{
    private static ?PDO $pdo = null;

    /**
     * Retourneert een gedeelde PDO-instantie.
     * Initialiseert de verbinding als deze nog niet bestaat.
     * * @return PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $config = require __DIR__ . '/../Config/database.php';

        // Verbeterde DSN string met expliciete poort configuratie
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'] ?? '3306',
            $config['dbname'],
            $config['charset']
        );

        try {
            self::$pdo = new PDO(
                $dsn,
                $config['user'],
                $config['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Log de fout en stop de uitvoering bij een databasefout
            http_response_code(500);
            error_log("Database verbinding mislukt: " . $e->getMessage());
            echo '<h1>500 - Interne serverfout: Databaseverbinding mislukt</h1>';
            exit;
        }

        return self::$pdo;
    }
}