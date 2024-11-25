<?php

use Neuralpin\HTTPRouter\Interface\ControllerWrapper;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function test_get_base_path()
    {
        $Route = new Route('/test/path');
        $this->assertEquals('/test/path', $Route->getBasePath());
    }

    public function test_add_controller()
    {
        $Route = new Route('/test/path');
        $Controller = fn () => 'Response test';
        $Route->addController('GET', $Controller);

        $this->assertSame($Controller, $Route->getController('GET'));
    }

    public function test_path_matches()
    {
        $Route = new Route('/test/:id');
        $this->assertTrue($Route->pathMatches('/test/123'));
        $this->assertFalse($Route->pathMatches('/test/'));
    }

    public function test_method_matches()
    {
        $Route = new Route('/test/path');
        $Route->addController('GET', fn () => null);
        $this->assertTrue($Route->methodMatches('GET'));
        $this->assertFalse($Route->methodMatches('POST'));
    }

    public function test_get_controller_wrapped()
    {
        $Route = new Route('/test/path', 'GET', fn () => null);
        $requestState = $this->createMock(RequestState::class);
        $requestState->method('getMethod')->willReturn('GET');
        $requestState->method('getPath')->willReturn('/test/path');

        $controllerWrapper = $Route->getControllerWrapped($requestState);
        $this->assertInstanceOf(ControllerWrapper::class, $controllerWrapper);
    }

    public function test_bind_params()
    {
        $Route = new Route('/test/:id/:name');
        $params = $Route->bindParams('/test/123/john');
        $expected = ['id' => '123', 'name' => 'john'];
        $this->assertEquals($expected, $params);
    }

    public function test_ignore_param_slash()
    {
        $Route = new Route('/test/:param');
        $Route->ignoreParamSlash();
        $this->assertTrue($Route->pathMatches('/test/anything/else'));
    }
}
