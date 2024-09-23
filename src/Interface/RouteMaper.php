<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMaper
{
    /**
     * @return ControllerMaper[]
     */
    public function getRoutes(): array;
}
