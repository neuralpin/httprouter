<?php

namespace Neuralpin\HTTPRouter\Helper;

use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Helper\RequestDataHelper;

class RequestData implements RequestState
{
    public function __construct(
        protected array $headers = [],
        protected object|array|null $body = [],
        protected string $method = 'get',
        protected string $path = '/',
        protected array $queryParams = [],
    ) {
        $this->method = strtolower($this->method);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): object|array|null
    {
        return $this->body;
    }

    public function getInput(string $name): mixed
    {
        return $this->body[$name] ?? null;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParam(string $name): mixed
    {
        return $this->queryParams[$name] ?? null;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public static function createFromGlobals(?string $bodyRequestString = null): RequestState
    {

        $queryString = $_SERVER['QUERY_STRING'] ?? '';

        $RequestDataHelper = new RequestDataHelper;
        $bodyRequestString ??= $RequestDataHelper->getBodyString();

        return new self(
            headers: $RequestDataHelper->getAllHeaders(),
            body: json_decode($bodyRequestString, true),
            queryParams: $RequestDataHelper->getQueryParams($queryString),
            method: $_SERVER['REQUEST_METHOD'],
            path: strtok(trim($_SERVER['REQUEST_URI'], '/') ?? '/', '?'),
        );
    }

}
