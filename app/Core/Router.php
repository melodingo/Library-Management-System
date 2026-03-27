<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $getRoutes = [];
    private array $postRoutes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->getRoutes[$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->postRoutes[$path] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $routes = strtoupper($method) === 'POST' ? $this->postRoutes : $this->getRoutes;

        if (!isset($routes[$path])) {
            http_response_code(404);
            echo '404 - Seite nicht gefunden';
            return;
        }

        $handler = $routes[$path];

        if (is_array($handler)) {
            [$className, $action] = $handler;
            $controller = new $className();
            $controller->{$action}();
            return;
        }

        $handler();
    }
}
