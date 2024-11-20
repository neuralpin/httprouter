<?php

namespace Neuralpin\HTTPRouter;

use Exception;
use Stringable;
use Neuralpin\HTTPRouter\ControllerWrapped;
use Neuralpin\HTTPRouter\Helper\RequestData;
use Neuralpin\HTTPRouter\Interface\RouteMapper;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\RouteMatcher;
use Neuralpin\HTTPRouter\Interface\ResponseState;
use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Neuralpin\HTTPRouter\Exception\NotFoundException;
use Neuralpin\HTTPRouter\Interface\ControllerWrapper;
use Neuralpin\HTTPRouter\Exception\MethodNotAllowedException;

class Router implements RouteMatcher
{
    protected RouteMapper $RouteCollection;
    
    /**
     * Summary of ControllerWrapper
     * @var class-string<ControllerWrapper> $ControllerWrapper
     */
    protected string $ControllerWrapper;

    protected RequestState $RequestState;

    /**
     * Summary of __construct
     * @param class-string<RouteMapper> $RouteCollection
     * @param class-string<ControllerMapper> $ControllerMapper
     * @param class-string<ControllerWrapper> $ControllerWrapper
     * @param ?RequestState $RequestState
     */
    public function __construct(
        ?string $RouteCollection = RouteCollection::class,
        ?string $ControllerMapper = Route::class,
        ?string $ControllerWrapper = ControllerWrapped::class,
        ?RequestState $RequestState = null,
    )
    {
        $this->RouteCollection = new $RouteCollection();
        $this->RouteCollection->setControllerMapper($ControllerMapper);
        $this->ControllerWrapper = $ControllerWrapper;
        $RequestState ??= RequestData::createFromGlobals();
        $this->RequestState = $RequestState;
    }

    public function isMethodNotAllowedException( Exception $Exception ): bool
    {
        return $Exception instanceof MethodNotAllowedException;
    }

    public function isNotFoundException( Exception $Exception ): bool
    {
        return $Exception instanceof NotFoundException;
    }

    public function getController(?RouteMapper $RouteMapper = null, ?RequestState $RequestState = null): ControllerWrapper
    {
        $RouteMapper ??= $this->RouteCollection;

        $RequestState ??= $this->RequestState;

        foreach ($RouteMapper->getRoutes() as $Route) {
            $urlMatches = $Route->pathMatches($RequestState->getPath());
            $methodMatches = $Route->methodMatches($RequestState->getMethod());

            if ($urlMatches && ! $methodMatches) {
                throw new MethodNotAllowedException('Method not allowed');
            }

            if ($urlMatches && $methodMatches) {
                return $Route->getController($RequestState);
            }
        }

        throw new NotFoundException; // Throws 404 error when route doesn't exists
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @param  T  $ControllerMapper
     * @return T
     */
    public function any(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('any', $path, $callable);
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @param  T  $ControllerMapper
     * @return T
     */
    public function get(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('get', $path, $callable);
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @return T
     */
    public function post(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('post', $path, $callable);
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @return T
     */
    public function put(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('put', $path, $callable);
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @return T
     */
    public function patch(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('patch', $path, $callable);
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @return T
     */
    public function delete(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('delete', $path, $callable);
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @return T
     */
    public function options(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('options', $path, $callable);
    }

    /**
     * Add new route to the collection
     *
     * @template T of ControllerMapper
     *
     * @param  callable(mixed ...): (ResponseState|Stringable|string|scalar|null)  $callable
     * @return T
     */
    public function head(string $path, object|array $callable)
    {
        return $this->RouteCollection->addRoute('head', $path, $callable);
    }

    /**
     * Summary of wrapController
     * @param callable(mixed...): (ResponseState|Stringable|string|scalar|null) $Controller
     * @param RequestState|null $RequestState
     * @return ControllerWrapper
     */
    public function wrapController(array|object $Controller, ?RequestState $RequestState = null): ControllerWrapper
    {
        $RequestState ??= $this->RequestState;

        $ControllerWrapped = new $this->ControllerWrapper();
        $ControllerWrapped->setController($Controller);
        $ControllerWrapped->setState($RequestState);

        return $ControllerWrapped;
    }
}
