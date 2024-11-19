<?php

namespace Neuralpin\HTTPRouter\Interface;

interface ControllerMapper
{
    public function addController(string $method, array|object $controller);

    public function getController(RequestState $RequestState): ?ControllerWrapper;

    public function pathMatches(string $path): bool;

    public function methodMatches(string $method): bool;
}
