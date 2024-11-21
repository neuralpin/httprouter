<?php

namespace Neuralpin\HTTPRouter\Helper;

use Neuralpin\HTTPRouter\Interface\RequestState;

class RequestData implements RequestState
{
    protected array $headers = [];
    protected object|array|null $body = [];
    protected string $method = 'get';
    protected string $path = '/';
    protected array $queryParams = [];

    public function setHeaders(array $headers = [])
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setBody(object|array|null $body = [])
    {
        $this->body = $body;
    }

    public function getBody(): object|array|null
    {
        return $this->body;
    }

    public function setMethod(string $method = 'get')
    {
        $this->method = strtolower($method);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setPath(string $path = '')
    {
        $this->path = strtolower($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setQueryParams(array $params = [])
    {
        $this->queryParams = $params;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }


    public function getParam(string $name): mixed
    {
        return $this->queryParams[$name] ?? null;
    }

    public function getInput(string $name): mixed
    {
        return $this->body[$name] ?? null;
    }
}
