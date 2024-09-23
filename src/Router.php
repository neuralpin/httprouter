<?php

namespace Neuralpin\HTTPRouter;

use Neuralpin\HTTPRouter\Exception\MethodNotAllowedException;
use Neuralpin\HTTPRouter\Interface\ControllerWrapper;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\RouteMaper;
use Neuralpin\HTTPRouter\Interface\RouteMatcher;

class Router implements RouteMatcher
{
    public function getController(RouteMaper $RouteMaper, RequestState $RequestState): ?ControllerWrapper
    {
        foreach ($RouteMaper->getRoutes() as $Route) {
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
