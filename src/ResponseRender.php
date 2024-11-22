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

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setQueryParams(array $params): static
    {
        $this->params = $params;
        return $this;
    }

    public function getQueryParams(): array
    {
        return $this->params;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setPath(string $path): static
    {
        $this->path = trim($path, '/');
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
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
