<?php

namespace Neuralpin\HTTPRouter;

use Neuralpin\HTTPRouter\Exception\MethodNotAllowedException;
use Neuralpin\HTTPRouter\Interface\ControllerWrapper;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\RouteMapper;
use Neuralpin\HTTPRouter\Interface\RouteMatcher;

class Router implements RouteMatcher
{
    public function getController(RouteMapper $RouteMapper, RequestState $RequestState): ?ControllerWrapper
    {
        foreach ($RouteMapper->getRoutes() as $Route) {
            $urlMatches = $Route->pathMatches($RequestState->getPath());
            $methodMatches = $Route->methodMatches($RequestState->getMethod());

            if ($urlMatches && ! $methodMatches) {
                throw new MethodNotAllowedException('Method not allowed');
            }

            if ($urlMatches && $methodMatches) {
                return $Route->getController($RequestState);
            }
        }

        return null;
    }
}
