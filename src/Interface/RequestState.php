<?php

namespace Neuralpin\HTTPRouter\Interface;

interface RequestState
{
    public function setHeaders(array $headers);

    public function getHeaders(): array;

    public function setBody(object|array|null $body);

    public function getBody(): object|array|null;

    public function setQueryParams(array $params);

    public function getQueryParams(): array;

    public function setMethod(string $method);

    public function getMethod(): string;

    public function setPath(string $path);

    public function getPath(): string;

    // public function getInput(string $name);
}
