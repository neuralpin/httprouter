<?php

declare(strict_types=1);

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

    protected array $routeParameters = [];

    protected RequestState $RequestState;

    /**
     * @param  null|callable(mixed...): mixed  $Controller
     */
    public function wrapController(
        null|array|object $Controller
    ): static {
        if (
            is_array($Controller) && (! class_exists($Controller[0]) || ! method_exists($Controller[0], $Controller[1] ?? ''))
            || is_object($Controller) && ! is_callable($Controller)
        ) {
            throw new InvalidControllerException('Route controller is not a valid callable or it can not be called from the actual scope');
        }

        if (is_array($Controller)) {
            $Controller = [new $Controller[0], $Controller[1]];
        }

        $this->Controller = $Controller;

        return $this;
    }

    public function getUnwrappedController(): array|object|null
    {
        return $this->Controller;
    }

    public function setState(
        RequestState $RequestState,
    ): static {
        $this->RequestState = $RequestState;

        return $this;
    }

    public function getState(): RequestState
    {
        return $this->RequestState;
    }

    public function setParameters(
        array $routeParameters = [],
    ) {
        $this->routeParameters = $routeParameters;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->routeParameters;
    }

    public function getResponse(): ?ResponseState
    {

        $params = $this->resolveParams($this->Controller, $this->RequestState, $this->routeParameters);

        ob_start();
        $Result = call_user_func_array($this->Controller, $params);
        ob_end_clean();

        $shouldAdaptResponse = (
            gettype($Result) === 'object'
            && ! $Result instanceof ResponseState
            && $Result instanceof Stringable
        ) || is_scalar($Result);

        if ($shouldAdaptResponse) {
            $status = empty($Result) ? 204 : 200;
            $body = (string) $Result;
            $Result = new ResponseRender($body, $status);
        }

        if ($Result instanceof ResponseState) {
            $Result->setQueryParams($this->RequestState->getQueryParams());
            $Result->setParams($params);
            $Result->setMethod($this->RequestState->getMethod());
            $Result->setPath($this->RequestState->getPath());

            return $Result;
        }

        return null;
    }

    public function resolveParams($Controller, RequestState $RequestState, $RouteParams): array
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
