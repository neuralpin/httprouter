<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RequestState
{
    public function getHeaders(): array;

    public function getBody(): object|array|null;

    public function getQueryParams(): array;

    public function getMethod(): string;

    public function getPath(): string;

    // public function getInput(string $name);
}
