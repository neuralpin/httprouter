<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMapperExtended extends ControllerMapper
{
    public function ignoreParamSlash();
}
