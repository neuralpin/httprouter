<?php

namespace Neuralpin\HTTPRouter\Interface;

use Stringable;

interface ControllerWrapper
{
    public function getResponse(): ResponseState|null;

    /**
     * @param  null|callable(mixed...): mixed  $Controller
     */
    public function wrapController(null|array|object $Controller);

    public function getUnwrappedController(): null|array|object;

    public function setState(RequestState $RequestState);

    public function getState(): RequestState;

    public function setParameters(array $RouteParams);

    public function getParameters(): array;
}
