<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMapper
{
    /**
     * @return array<string, ControllerMapper>
     */
    public function getRoutes(): array;
}
