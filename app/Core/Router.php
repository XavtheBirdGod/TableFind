<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router Class
 * Verantwoordelijk voor het routeren van verzoeken naar de juiste controllers.
 */
final class Router
{
    private array $routes = [];

    /**
     * Voegt een route toe.
     */
    public function add(string $method, string $path, string $handler): void
    {
        // Zet parameters zoals {id} om naar regex
        $pattern = preg_replace('/\{id\}/', '(\d+)', $path);
        
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => "#^" . $pattern . "$#",
            'handler' => $handler
        ];
    }

    /**
     * Analyseert de URI en voert de bijbehorende controller uit.
     */
    public function dispatch(string $uri, string $method): void
    {
        // Verwijder query parameters voor matching
        $path = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $path, $matches)) {
                array_shift($matches); 
                
                [$controllerName, $action] = explode('@', $route['handler']);
                $controllerClass = "App\\Controllers\\" . $controllerName;

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $action)) {
                        call_user_func_array([$controller, $action], $matches);
                        return;
                    }
                }
            }
        }

        // Als er geen match is, toon 404
        // Als er geen match is, toon 404 via de ErrorController
        $errorController = new \App\Controllers\ErrorController();
        $errorController->notFound();
    }
}