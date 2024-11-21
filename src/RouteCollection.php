<?php

namespace Neuralpin\HTTPRouter;

use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Neuralpin\HTTPRouter\Interface\ResponseState;
use Neuralpin\HTTPRouter\Interface\RouteMapper;
use Stringable;

class RouteCollection implements RouteMapper
{
    public readonly string $ControllerMapper;

    public function setControllerMapper(string $ControllerMapper): void
    {
        $this->ControllerMapper = $ControllerMapper;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @var array<string, ControllerMapper>
     */
    protected array $routes = [];

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @return T
     */
    public function addRoute(string $method, string $path, object|array $callable): ControllerMapper
    {
        $this->routes[$path] ??= new $this->ControllerMapper(trim($path, '/'));
        $this->routes[$path]->addController($method, $callable);

        return $this->routes[$path];
    }
}
