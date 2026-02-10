<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Security Class
 * Biedt methoden voor input sanitization en output escaping om XSS te voorkomen.
 */
class Security
{
    /**
     * Escapet HTML output om XSS te voorkomen.
     * @param string|null $value De te escapen waarde.
     * @return string De veilige string.
     */
    public static function escape(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Schoont input data op (recursive).
     * @param mixed $data De input data (string of array).
     * @return mixed De opgeschoonde data.
     */
    public static function sanitize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        }

        if (is_string($data)) {
            $data = trim($data);
            // Optioneel: strip_tags($data) als je geen enkele HTML toestaat
            // $data = strip_tags($data); 
            // Voor nu houden we het bij trimmen en vertrouwen we op escaping bij output
        }

        return $data;
    }

    /**
     * Genereert een CSRF token voor formulieren.
     * (Eenvoudige implementatie voor latere uitbreiding)
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Valideert een CSRF token.
     */
    public static function validateCsrfToken(?string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
    }
}
