<?php

declare(strict_types=1);

use Neuralpin\HTTPRouter\Exception\MethodNotAllowedException;
use Neuralpin\HTTPRouter\Exception\NotFoundException;
use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\RouteMapper;
use Neuralpin\HTTPRouter\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected Router $router;

    protected function setUp(): void
    {
        $this->router = new Router;
    }

    public function test_is_method_not_allowed_exception()
    {
        $exception = new MethodNotAllowedException;
        $this->assertTrue($this->router->isMethodNotAllowedException($exception));
    }

    public function test_is_not_found_exception()
    {
        $exception = new NotFoundException;
        $this->assertTrue($this->router->isNotFoundException($exception));
    }

    public function test_get_controller_throws_method_not_allowed_exception()
    {
        $this->expectException(MethodNotAllowedException::class);

        $routeMapper = $this->createMock(RouteMapper::class);
        $routeMapper->method('getRoutes')->willReturn([
            $this->createMockRoute('/test', 'POST'),
        ]);

        $requestState = $this->createMock(RequestState::class);
        $requestState->method('getPath')->willReturn('/test');
        $requestState->method('getMethod')->willReturn('GET');

        $this->router->getController($routeMapper, $requestState);
    }

    public function test_get_controller_throws_not_found_exception()
    {
        $this->expectException(NotFoundException::class);

        $routeMapper = $this->createMock(RouteMapper::class);
        $routeMapper->method('getRoutes')->willReturn([]);

        $requestState = $this->createMock(RequestState::class);
        $requestState->method('getPath')->willReturn('/non-existent');

        $this->router->getController($routeMapper, $requestState);
    }

    public function test_any_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->any('/any', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    public function test_get_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->get('/get', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    public function test_post_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->post('/post', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    public function test_put_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->put('/put', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    public function test_patch_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->patch('/patch', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    public function test_delete_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->delete('/delete', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    public function test_options_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->options('/options', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    public function test_head_method()
    {
        $callable = function () {
            return 'response';
        };
        $route = $this->router->head('/head', $callable);
        $this->assertInstanceOf(ControllerMapper::class, $route);
    }

    protected function createMockRoute(string $path, string $method)
    {
        $route = $this->createMock(ControllerMapper::class);
        $route->method('pathMatches')->willReturnCallback(function ($inputPath) use ($path) {
            return $inputPath === $path;
        });
        $route->method('methodMatches')->willReturnCallback(function ($inputMethod) use ($method) {
            return $inputMethod === $method;
        });

        return $route;
    }
}
