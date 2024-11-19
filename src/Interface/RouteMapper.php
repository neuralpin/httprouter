<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMapper
{
    /**
     * @return ControllerMapper[]
     */
    public function getRoutes(): array;
}
