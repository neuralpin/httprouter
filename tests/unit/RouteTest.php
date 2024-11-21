<?php

use Neuralpin\HTTPRouter\Route;
use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\RequestData;
use Neuralpin\HTTPRouter\ControllerWrapped;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Helper\RequestDataHelper;
use Neuralpin\HTTPRouter\Interface\ControllerWrapper;

class RouteTest extends TestCase
{
    public function testGetBasePath()
    {
        $Route = new Route('/test/path');
        $this->assertEquals('/test/path', $Route->getBasePath());
    }

    public function testAddController()
    {
        $Route = new Route('/test/path');
        $Controller = fn()=> 'Response test';
        $Route->addController('GET', $Controller);
        
        $this->assertSame($Controller, $Route->getController('GET'));
    }

    public function testPathMatches()
    {
        $Route = new Route('/test/:id');
        $this->assertTrue($Route->pathMatches('/test/123'));
        $this->assertFalse($Route->pathMatches('/test/'));
    }

    public function testMethodMatches()
    {
        $Route = new Route('/test/path');
        $Route->addController('GET', fn()=>null);
        $this->assertTrue($Route->methodMatches('GET'));
        $this->assertFalse($Route->methodMatches('POST'));
    }

    public function testGetControllerWrapped()
    {
        $Route = new Route('/test/path', 'GET', fn()=>null);
        $requestState = $this->createMock(RequestState::class);
        $requestState->method('getMethod')->willReturn('GET');
        $requestState->method('getPath')->willReturn('/test/path');

        $controllerWrapper = $Route->getControllerWrapped($requestState);
        $this->assertInstanceOf(ControllerWrapper::class, $controllerWrapper);
    }

    public function testBindParams()
    {
        $Route = new Route('/test/:id/:name');
        $params = $Route->bindParams('/test/123/john');
        $expected = ['id' => '123', 'name' => 'john'];
        $this->assertEquals($expected, $params);
    }

    public function testIgnoreParamSlash()
    {
        $Route = new Route('/test/:param');
        $Route->ignoreParamSlash();
        $this->assertTrue($Route->pathMatches('/test/anything/else'));
    }
}