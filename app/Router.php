<?php

namespace App;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            $controllerClass = $handler[0];
            $action = $handler[1];
            
            $controller = new $controllerClass();
            $controller->$action();
            return;
        }

        // 404 Not Found
        http_response_code(404);
        view('errors/404'); // Assuming this view exists, otherwise we'll output text or fallback. Let's fallback gracefully.
        echo "404 Not Found - The requested URL was not found on this server.";
        exit;
    }
}
