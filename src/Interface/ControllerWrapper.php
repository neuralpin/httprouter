<?php

namespace Neuralpin\HTTPRouter\Interface;

use Stringable;
use Neuralpin\HTTPRouter\Interface\ResponseState;

interface ControllerWrapper
{
    public function getResponse(): null|string|Stringable|ResponseState;
}
