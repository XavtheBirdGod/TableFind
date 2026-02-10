<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router Class - Multi-platform compatible.
 * Verantwoordelijk voor het matchen van de URI aan de juiste controller actie.
 */
class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][] = [
            'path' => '/' . ltrim($path, '/'),
            'handler' => $handler
        ];
    }

    /**
     * Verwerkt de inkomende aanvraag en voert de controller uit.
     */
    public function dispatch(string $uri, string $method): void
    {
        // 1. Verwijder query strings (?id=1)
        $uri = strtok($uri, '?');

        // 2. Cross-platform Base Path detectie
        // Hiermee werkt /tablefind/public/book hetzelfde als /book
        $scriptName = $_SERVER['SCRIPT_NAME']; 
        $basePath = str_replace('/index.php', '', $scriptName);
        
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Normaliseer de URI
        $uri = '/' . ltrim($uri, '/');

        foreach ($this->routes[$method] ?? [] as $route) {
            if ($route['path'] === $uri) {
                $handler = $route['handler'];
                
                if (is_array($handler)) {
                    [$controllerClass, $methodName] = $handler;
                    // Maak een instantie van de controller
                    $controller = new $controllerClass();
                    $controller->$methodName();
                } else {
                    $handler();
                }
                return;
            }
        }

        // Fallback als route niet bestaat
        http_response_code(404);
        echo "<h1>404 - Pagina niet gevonden</h1>";
        echo "<p>Route <strong>$uri</strong> is niet gedefinieerd.</p>";
    }
}