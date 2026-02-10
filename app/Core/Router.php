<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router Class
 * Verantwoordelijk voor het routeren van HTTP-verzoeken naar de juiste controllers.
 */
class Router
{
    private array $routes = [];

    /**
     * Voegt een GET-route toe.
     */
    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Voegt een POST-route toe.
     */
    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][] = [
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Stuurt het verzoek door naar de juiste handler.
     */
    public function dispatch(string $uri, string $method): void
    {
        $uri = strtok($uri, '?');

        foreach ($this->routes[$method] ?? [] as $route) {
            // Eenvoudige matching (kan uitgebreid worden voor parameters)
            if ($route['path'] === $uri) {
                $handler = $route['handler'];
                if (is_array($handler)) {
                    [$controller, $methodName] = $handler;
                    $controller->$methodName();
                } else {
                    $handler();
                }
                return;
            }
        }

        http_response_code(404);
        echo "404 - Pagina niet gevonden";
    }
}