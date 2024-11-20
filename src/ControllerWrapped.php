<?php

namespace Neuralpin\HTTPRouter;

use DomainException;
use Neuralpin\HTTPRouter\Exception\InvalidControllerException;
use Neuralpin\HTTPRouter\Interface\ControllerWrapper;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\ResponseState;
use ReflectionFunction;
use ReflectionMethod;
use Stringable;

class ControllerWrapped implements ControllerWrapper
{
    protected null|array|object $Controller;
    protected string $method;
    protected string $path;
    protected array $queryParams;
    protected array $routeParameters = [];
    protected RequestState $RequestState;

    public function setController(
        null|array|object $Controller
    ): void
    {
        if (
            is_array($Controller) && (! class_exists($Controller[0]) || ! method_exists($Controller[0], $Controller[1]))
            || is_object($Controller) && ! is_callable($Controller)
        ) {
            throw new InvalidControllerException('Route controller is not a valid callable or it can not be called from the actual scope');
        }

        if (is_array($Controller)) {
            $Controller = [new $Controller[0], $Controller[1]];
        }

        $this->Controller = $Controller;
    }

    public function setState(
        RequestState $RequestState,
    ): void
    {
        $this->RequestState = $RequestState;

        $this->method = $this->RequestState->getMethod();
        $this->path = $this->RequestState->getPath();
        $this->queryParams = $this->RequestState->getQueryParams();
    }

    public function setParams(
        array $routeParameters = [],
    ): void 
    {
        $this->routeParameters = $routeParameters;
    }

    public function getResponse(): null|ResponseState|Stringable
    {

        $params = $this->resolveParams($this->Controller, $this->RequestState, $this->routeParameters);

        ob_start();
        $Result = call_user_func_array($this->Controller, $params);
        ob_clean();

        $ResultType = gettype($Result);

        if ($ResultType === 'object' && $Result instanceof ResponseState) {
            $Result->setQueryParams($this->queryParams);
            $Result->setParams($params);
            $Result->setMethod($this->method);
            $Result->setPath($this->path);

            return $Result;
        } elseif (
            is_scalar($Result)
            || ($ResultType === 'object' && $Result instanceof Stringable)
        ) {
            $status = empty($Result) ? 204 : 200;
            $body = (string) $Result;

            return new ResponseRender($body, $status);
        }

        return null;
    }

    protected function resolveParams($Controller, RequestState $RequestState, $RouteParams): array
    {
        if (gettype($Controller) == 'object' && get_class($Controller) == 'Closure') {
            $reflection = new ReflectionFunction($Controller);
        } else {
            $reflection = new ReflectionMethod(...$Controller);
        }

        $params = [];
        foreach ($reflection->getParameters() as $parameter) {
            $paramName = $parameter->getName();

            // Request state  injection
            if ($parameter->getType()?->getName() === get_class($RequestState)) {
                $params[$paramName] = $RequestState;

                continue;
            }

            if (! isset($RouteParams[$paramName])) {
                throw new DomainException("Cannot resolve the parameter: '{$paramName}'");
            }

            $params[$paramName] = $RouteParams[$paramName];
        }

        return $params;
    }
}
