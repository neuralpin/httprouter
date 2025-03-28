<?php

namespace Neuralpin\HTTPRouter\Interface;

/**
 * @template T of ControllerMapper
 */
interface RouteMapper
{
    /**
     * Inject ControllerMapper dependency
     *
     * @param  class-string<ControllerMapper>  $ControllerMapper
     */
    public function setControllerMapper(string $ControllerMapper);

    /**
     * Return the ControllerMapper class name
     *
     * @return class-string<ControllerMapper>
     */
    public function getControllerMapper(): string;

    /**
     * Return the route-map collection
     *
     * @return array<string, ControllerMapper>
     */
    public function getRoutes(): array;

    /**
     * Add new route to the route-map collection
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|scalar|null)  $callable
     * @return T
     */
    public function addRoute(string $method, string $path, object|array $callable): ControllerMapper;
}
