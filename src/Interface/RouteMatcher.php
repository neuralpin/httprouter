<?php

declare(strict_types=1);

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMatcher
{
    public function getController(RouteMapper $RouteMapper, RequestState $RequestState): ?ControllerWrapper;
}
