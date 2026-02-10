<?php
declare(strict_types=1);

namespace App\Core;

/**
 * View Engine - Vintage Edition
 */
class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        // Pad normaliseren voor Windows en Mac
        $viewPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view);
        $viewFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            die("Fout: View niet gevonden op: " . htmlspecialchars($viewFile));
        }

        // Bepaal layout: Admin of Public
        $layoutName = (str_contains(strtolower($view), 'admin')) ? 'admin' : 'public';

        // Output buffering om content te vangen
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Pad naar de layout
        $layoutPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Public' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'public.php';

        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            echo $content;
        }
    }
}