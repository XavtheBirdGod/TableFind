<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router Class
 * Verantwoordelijk voor het routeren van verzoeken naar de juiste controllers.
 * Nu met verbeterde regex voor optionele trailing slashes.
 */
final class Router
{
    private array $routes = [];

    /**
     * Voegt een route toe en normaliseert het pad.
     */
    public function add(string $method, string $path, string $handler): void
    {
        // Verwijder trailing slash van het pad voor consistentie
        $path = ($path !== '/') ? rtrim($path, '/') : $path;
        
        // Zet parameters zoals {id} om naar regex (\d+)
        $pattern = preg_replace('/\{id\}/', '(\d+)', $path);
        
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => "#^" . $pattern . "/?$#", // Maakt trailing slash optioneel
            'handler' => $handler
        ];
    }

    /**
     * Analyseert de URI en voert de bijbehorende controller uit.
     */
    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = ($path !== '/') ? rtrim($path, '/') : $path;
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

        $errorController = new \App\Controllers\ErrorController();
        $errorController->notFound();
    }
}