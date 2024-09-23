<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RouteMatcher
{
    public function getController(RouteMaper $RouteMaper, RequestState $RequestState): ?ControllerWrapper;
}
