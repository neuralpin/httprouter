<?php

namespace Neuralpin\HTTPRouter;

use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Neuralpin\HTTPRouter\Interface\RouteMapper;

class RouteCollection implements RouteMapper
{
    public function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * @var ControllerMapper[]
     */
    public static array $routes = [];

    public static function addRoute(string $method, string $path, object|array $callable): Route
    {
        self::$routes[$path] ??= new Route(trim($path, '/'));
        self::$routes[$path]->addController($method, $callable);

        return self::$routes[$path];
    }

    public static function any(string $path, object|array $callable): Route
    {
        return self::addRoute('any', $path, $callable);
    }

    public static function get(string $path, object|array $callable): Route
    {
        return self::addRoute('get', $path, $callable);
    }

    public static function post(string $path, object|array $callable): Route
    {
        return self::addRoute('post', $path, $callable);
    }

    public static function put(string $path, object|array $callable): Route
    {
        return self::addRoute('put', $path, $callable);
    }

    public static function patch(string $path, object|array $callable): Route
    {
        return self::addRoute('patch', $path, $callable);
    }

    public static function delete(string $path, object|array $callable): Route
    {
        return self::addRoute('delete', $path, $callable);
    }

    public static function options(string $path, object|array $callable): Route
    {
        return self::addRoute('options', $path, $callable);
    }

    public static function head(string $path, object|array $callable): Route
    {
        return self::addRoute('head', $path, $callable);
    }
}
