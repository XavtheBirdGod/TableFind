<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

/**
 * ErrorController
 * Beheert de weergave van foutmeldingen.
 */
final class ErrorController
{
    /**
     * Toont de 404-foutpagina.
     */
    public function notFound(): void
    {
        http_response_code(404);
        View::render('Errors/404', [
            'title' => 'Pagina Niet Gevonden'
        ]);
    }
}