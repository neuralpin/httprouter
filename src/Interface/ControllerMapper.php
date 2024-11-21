<?php

namespace Neuralpin\HTTPRouter\Interface;

use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\ControllerWrapper;

interface ControllerMapper
{
    public function addController(string $method, array|object $controller);

    public function getControllerWrapped(RequestState $RequestState): ?ControllerWrapper;

    public function pathMatches(string $path): bool;

    public function methodMatches(string $method): bool;
}
