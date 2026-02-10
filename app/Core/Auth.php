<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Auth Class
 * Verantwoordelijk voor het beheren van de gebruikerssessies en autorisatie.
 */
class Auth
{
    /**
     * Controleert of de gebruiker is geauthenticeerd.
     * Gebruikt de standaard sessiesleutel 'user_id'.
     */
    public static function check(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    /**
     * Controleert of de ingelogde gebruiker een admin is (ID 1).
     * Gebruikt de standaard sessiesleutel 'user_role'.
     */
    public static function isAdmin(): bool
    {
        // De rol moet expliciet integer 1 zijn
        return self::check() && (int)($_SESSION['user_role'] ?? 0) === 1;
    }

    /**
     * Verplicht inloggen; stuurt onbevoegde gebruikers naar de loginpagina.
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }
}