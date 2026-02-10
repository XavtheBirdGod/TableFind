<?php
declare(strict_types=1);

namespace App\Core;

/**
 * View Engine
 * Verantwoordelijk voor het veilig renderen van templates en layouts.
 */
class View
{
    /**
     * Rendert een specifieke view en injecteert de meegegeven data.
     */
    public static function render(string $view, array $data = []): void
    {
        // Variabelen uitpakken zodat ze direct beschikbaar zijn in alle componenten
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $data['csrf_token'] = Security::generateCsrfToken();
        extract($data);

        $viewPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view);
        $basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR;
        $viewFile = $basePath . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(404);
            die("Systeemfout: De weergave '$view' kon niet worden gevonden.");
        }

        $isAdmin = str_contains(strtolower($view), 'admin');
        
        // Start buffering om het hoofd-inhoudsdeel op te vangen
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($isAdmin) {
            $header = $basePath . 'Admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';
            $footer = $basePath . 'Admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'footer.php';

            // Zorg dat de data ook beschikbaar is in header en footer
            if (file_exists($header)) {
                require $header; 
            }
            
            // De content wordt nu getoond binnen de structuur van de header/main
            echo $content;

            if (file_exists($footer)) {
                require $footer;
            }
        } else {
            $layoutPath = $basePath . 'Public' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'public.php';
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                echo $content;
            }
        }
    }
}