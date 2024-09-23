<?php

namespace Neuralpin\HTTPRouter;

use Neuralpin\HTTPRouter\Interface\ResponseState;
use Stringable;

class ResponseRender implements ResponseState, Stringable
{
    protected array $headers = [];

    protected string $body = '';

    protected int $status = 200;

    protected array $params = [];

    protected array $queryParams = [];

    protected string $method = 'get';

    protected string $path = '';

    public function __construct(
        string $body = '',
        int $status = 200,
        array $headers = [],
    ) {
        $this->body = $body;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setQueryParams(array $params)
    {
        $this->params = $params;
    }

    public function getQueryParams(): array
    {
        return $this->params;
    }

    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setUpStatus()
    {
        http_response_code($this->status);
    }

    public function setUpHeaders()
    {
        foreach ($this->headers as $header) {
            header($header);
        }
    }

    public function __toString(): string
    {
        $this->setUpStatus();
        $this->setUpHeaders();

        return (string) $this->getBody();
    }
}
