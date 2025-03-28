<?php

namespace Neuralpin\HTTPRouter;

use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Neuralpin\HTTPRouter\Interface\ResponseState;
use Neuralpin\HTTPRouter\Interface\RouteMapper;
use Stringable;

class RouteCollection implements RouteMapper
{
    private string $ControllerMapper;

    /**
     * Inject ControllerMapper Dependency
     *
     * @param  class-string<ControllerMapper>  $ControllerMapper
     */
    public function setControllerMapper(string $ControllerMapper): void
    {
        $this->ControllerMapper = $ControllerMapper;
    }

    public function getControllerMapper(): string
    {
        return $this->ControllerMapper;
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
     * @param  callable(mixed ...): (ResponseState|Stringable|scalar|null)  $callable
     */
    public function addRoute(string $method, string $path, object|array $callable): ControllerMapper
    {
        $this->routes[$path] ??= new $this->ControllerMapper(trim($path, '/'));
        $this->routes[$path]->addController($method, $callable);

        return $this->routes[$path];
    }
}
