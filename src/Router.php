<?php

namespace Neuralpin\HTTPRouter;

class Router
{
    private static array $routes = [];

    public static function setRoute(string $uri, callable $controller, string $method): void
    {
        self::$routes[$uri][$method] = $controller;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function any(string $uri, callable $controller): void
    {
        self::setRoute($uri, $controller, 'any');
    }
    public static function get(string $uri, callable $controller): void
    {
        self::setRoute($uri, $controller, 'GET');
    }
    public static function post(string $uri, $controller): void
    {
        self::setRoute($uri, $controller, 'POST');
    }
    public static function patch(string $uri, $controller): void
    {
        self::setRoute($uri, $controller, 'PATCH');
    }
    public static function put(string $uri, $controller): void
    {
        self::setRoute($uri, $controller, 'PUT');
    }
    public static function delete(string $uri, $controller): void
    {
        self::setRoute($uri, $controller, 'DELETE');
    }
    public static function head(string $uri, $controller): void
    {
        self::setRoute($uri, $controller, 'HEAD');
    }
    public static function options(string $uri, $controller): void
    {
        self::setRoute($uri, $controller, 'OPTIONS');
    }

    public static function match(string $uri = $_SERVER['REQUEST_URI'], string $method = $_SERVER['REQUEST_METHOD']): bool
    {
        $routeFound = [];
        $routeParams = [];

        // Find matching request uri
        foreach (self::$routes as $route => $controllers) {

            // Check if the route URL contains parameters
            if (strpos($route, ':') !== false) {
                $routeRegex = preg_replace('/:([a-zA-Z0-9]+)/', '([^/]+)', $routeUrl);
                $routeRegex = '/^' . str_replace('/', '\/', $routeRegex) . '$/';

                if (preg_match($routeRegex, $uri, $routeParams)) {
                    // Remove the first match, which is the entire URL
                    array_shift($routeParams);

                    preg_match_all('/:([a-zA-Z0-9]+)/', $routeUrl, $paramNames);
                    $routeParams = array_combine($paramNames[1], $routeParams);

                    $routeFound = $controllers;
                }
            } else {
                // If the route URL does not contain parameters, compare it directly to the URL
                if ($uri === $route) $routeFound = $controllers;
            }
        }

        // Find matching request method
        $routeAction = $routeFound[$method] ?? $routeFound['ANY'] ?? null;

        // Initialize object if a valid class name is given as route action
        if ( !is_null($routeAction) && is_array($routeAction) && is_string($routeAction[0]) ){
            $routeAction[0] = new $routeAction[0]();
        }

        // Execute route action and return result if the route action is a valid callable entity
        if(!is_null($routeAction) && is_callable($routeAction) ){
            return call_user_func_array($routeAction, $routeParams);
        }

        return false;
    }
}