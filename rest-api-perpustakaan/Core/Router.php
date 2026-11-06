<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, callable $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->getMethod();
        $uri = rtrim($request->getUri(), '/') ?: '/';

        if (!isset($this->routes[$method])) {
            Response::error("HTTP method $method tidak didukung", 405);
            return;
        }

        // Exact match
        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri], $request);
            return;
        }

        // Pattern match, contoh: /api/books/{id}
        foreach ($this->routes[$method] as $route => $handler) {
            $pattern = preg_replace(
                '#\{[^}]+\}#',
                '([^/]+)',
                $route
            );

            if (preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches);

                call_user_func_array(
                    $handler,
                    array_merge([$request], $matches)
                );

                return;
            }
        }

        Response::error("Endpoint tidak ditemukan: $uri", 404);
    }
}
