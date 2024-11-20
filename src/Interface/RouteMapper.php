<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMapper
{
    /**
     * Inject ControllerMapper dependency
     *
     * @param  class-string<ControllerMapper>  $ControllerMapper
     */
    public function setControllerMapper(string $ControllerMapper);

    /**
     * Return the route-map collection
     *
     * @return array<string, ControllerMapper>
     */
    public function getRoutes(): array;

    /**
     * Add new route to the route-map collection
     */
    public function addRoute(string $method, string $path, object|array $callable): ControllerMapper;
}
