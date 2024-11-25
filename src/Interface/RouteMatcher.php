<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMatcher
{
    public function getController(RouteMapper $RouteMapper, RequestState $RequestState): ?ControllerWrapper;
}
