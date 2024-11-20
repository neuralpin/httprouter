<?php

namespace Neuralpin\HTTPRouter;

use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Neuralpin\HTTPRouter\Interface\ControllerWrapper;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\RouteMapperExtended;

class Route implements RouteMapperExtended
{
    protected string $basePath;

    protected string $pattern;

    protected array $methods = [];

    public function __construct(
        string $basePath,
        ?string $method = null,
        null|object|array $controller = null,
    ) {
        $this->basePath = $basePath;
        $this->pattern = preg_replace('/:([a-zA-Z0-9]+)/', '([^/]+)', $this->basePath);
        $this->pattern = '/^'.str_replace('/', '\/', $this->pattern).'$/';

        if (isset($method, $controller)) {
            $this->addController($method, $controller);
        }
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function addController(string $method, object|array $controller): static
    {
        $this->methods[strtolower($method)] = $controller;

        return $this;
    }

    public function pathMatches(string $path): bool
    {
        return preg_match($this->pattern, $path);
    }

    public function methodMatches(string $method): bool
    {
        return (isset($this->methods[$method]) || isset($this->methods['any'])) ? true : false;
    }

    public function ignoreParamSlash(): ControllerMapper
    {
        $pattern = preg_replace('/:(.*)/', '(.*)', $this->basePath);
        $this->pattern = '/^'.str_replace('/', '\/', $pattern).'$/';

        return $this;
    }

    public function getController(RequestState $RequestData): ControllerWrapper
    {

        $Controller = $this->methods[$RequestData->getMethod()] ?? $this->methods['any'] ?? null;
        $RouteParams = $this->bindParams($RequestData->getPath());

        $ControllerWrapped = new ControllerWrapped;
        $ControllerWrapped->setController($Controller);
        $ControllerWrapped->setState($RequestData);
        $ControllerWrapped->setParams($RouteParams);

        return $ControllerWrapped;
    }

    public function bindParams(string $path): array
    {
        preg_match_all('/:([a-zA-Z0-9]+)/', $this->basePath, $paramNames, PREG_SET_ORDER);
        $paramNames = array_column($paramNames, 1);

        preg_match($this->pattern, $path, $uriParams);
        array_shift($uriParams);

        return array_combine($paramNames, $uriParams);
    }
}
