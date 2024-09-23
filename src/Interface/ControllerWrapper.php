<?php

namespace Neuralpin\HTTPRouter\Interface;

interface ControllerWrapper
{
    public function getResponse(): ?ResponseState;
}
