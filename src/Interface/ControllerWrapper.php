<?php

namespace Neuralpin\HTTPRouter\Interface;

use Stringable;

interface ControllerWrapper
{
    public function getResponse(): ResponseState|Stringable|string|scalar|null;

    /**
     * @param  null|callable(mixed...): mixed  $Controller
     */
    public function setController(null|array|object $Controller);

    public function setState(RequestState $RequestState);

    public function setParameters(array $RouteParams);
}
