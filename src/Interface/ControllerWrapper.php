<?php

namespace Neuralpin\HTTPRouter\Interface;

use Stringable;
use Neuralpin\HTTPRouter\Interface\RequestState;

interface ControllerWrapper
{
    public function getResponse(): ResponseState|Stringable|string|scalar|null;

    /**
     * @param callable(mixed...): (ResponseState|Stringable|string|scalar|null) $Controller
     */
    public function setController(null|array|object $Controller);

    public function setState(RequestState $RequestState);

    public function setParams(array $RouteParams);
}
