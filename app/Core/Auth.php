<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Auth Class
 * Een helper klasse voor het verifiëren van autorisatieniveaus binnen de applicatie.
 */
class Auth
{
    /**
     * Controleert of de huidige bezoeker is ingelogd.
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Controleert of de gebruiker de rol van 'admin' heeft (role_id 1 in DB).
     */
    public static function isAdmin(): bool
    {
        // In de database is Admin gekoppeld aan ID 1
        return self::check() && (int)$_SESSION['user_role'] === 1;
    }

    /**
     * Controleert of de gebruiker de rol van 'editor' heeft (role_id 2 in DB).
     */
    public static function isEditor(): bool
    {
        // In de database is Editor gekoppeld aan ID 2
        return self::check() && (int)$_SESSION['user_role'] === 2;
    }

    /**
     * Forceert authenticatie; stuurt onbevoegde gebruikers naar de loginpagina.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }
}