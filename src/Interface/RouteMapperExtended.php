<?php

declare(strict_types=1);

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMapperExtended extends ControllerMapper
{
    public function ignoreParamSlash();
}
