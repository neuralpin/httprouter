<?php

namespace Neuralpin\HTTPRouter\Interface;

use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Stringable;

interface RouteMapper
{

    /**
     * Inject ControllerMapper dependency
     * @param class-string<ControllerMapper> $ControllerMapper
     */
    public function setControllerMapper(string $ControllerMapper);

    /**
     * Return the route-map collection
     * @return array<string, ControllerMapper>
     */
    public function getRoutes(): array;

    /**
     * Add new route to the route-map collection
     * @param string $method
     * @param string $path
     * @param object|array $callable
     * @return ControllerMapper
     */
    public function addRoute(string $method, string $path, object|array $callable): ControllerMapper;
}
