<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Flash Class
 * Beheert tijdelijke succes- of foutmeldingen in de sessie.
 * Deze meldingen worden getoond na een redirect en daarna verwijderd.
 */
final class Flash
{
    /**
     * Slaat een bericht op in de sessie.
     * * @param string $message Het weer te geven bericht.
     * @param string $type Het type melding (bijv. 'success', 'danger', 'info').
     * @return void
     */
    public static function set(string $message, string $type = 'info'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash_message'] = [
            'message' => $message,
            'type'    => $type
        ];
    }

    /**
     * Haalt het opgeslagen bericht op en verwijdert het direct uit de sessie.
     * * @return array|null Het bericht en type, of null als er geen bericht is.
     */
    public static function get(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['flash_message'])) {
            $flash = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $flash;
        }

        return null;
    }

    /**
     * Controleert of er momenteel een flash-bericht aanwezig is.
     * * @return bool True als er een bericht is, anders false.
     */
    public static function has(): bool
    {
        return isset($_SESSION['flash_message']);
    }
}