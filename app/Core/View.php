<?php
declare(strict_types=1);

namespace App\Core;

/**
 * View Class
 * Verantwoordelijk voor het renderen van views binnen een lay-out.
 */
class View
{
    /**
     * Rendert een specifieke view.
     * @param string $view Pad naar de view bestand (bijv. 'Public/home')
     * @param array $data Data die naar de view wordt gestuurd
     */
    public static function render(string $view, array $data = []): void
    {
        // Data uitpakken naar variabelen
        extract($data);

        // Pad naar de content file
        $viewFile = __DIR__ . "/../Views/{$view}.php";

        if (!file_exists($viewFile)) {
            die("Fout: View bestand [{$view}] niet gevonden.");
        }

        // Bepaal de lay-out (Admin of Public)
        $layoutType = str_contains($view, 'Admin') ? 'admin' : 'public';

        // Start output buffering om de content op te vangen
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Laad de gekozen lay-out en injecteer de $content
        $layoutPath = __DIR__ . "/../Views/Public/layouts/{$layoutType}.php";

        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            // Fallback als lay-out niet bestaat
            echo $content;
        }
    }
}