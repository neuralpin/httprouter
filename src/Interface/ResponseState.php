<?php

namespace Neuralpin\HTTPRouter\Interface;

use Stringable;

interface ResponseState extends Stringable
{
    public function getHeaders(): array;

    public function getBody(): string;

    public function getStatus(): int;

    public function setParams(array $params);

    public function setQueryParams(array $queryParams);

    public function setMethod(string $method);

    public function setPath(string $path);
}
