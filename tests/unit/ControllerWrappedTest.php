<?php

use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\ControllerWrapped;
use Neuralpin\HTTPRouter\Demo\DemoController;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\ResponseState;
use Neuralpin\HTTPRouter\Exception\InvalidControllerException;

class ControllerWrappedTest extends TestCase
{
    // public function testSetController()
    // {
    //     $controllerWrapped = new ControllerWrapped();

    //     // Test with a valid callable array
    //     $controllerWrapped->setController([DemoController::class, 'get']);
    //     $this->assertIsArray($controllerWrapped->Controller);

    //     // Test with a valid callable object
    //     $controllerWrapped->setController(new DemoController());
    //     $this->assertIsObject($controllerWrapped->Controller);

    //     // Test with an invalid callable
    //     $this->expectException(InvalidControllerException::class);
    //     $controllerWrapped->setController([DemoController::class, 'invalidMethod']);
    // }

    // public function testSetState()
    // {
    //     $controllerWrapped = new ControllerWrapped();
    //     $requestState = $this->createMock(RequestState::class);
    //     $requestState->method('getMethod')->willReturn('GET');
    //     $requestState->method('getPath')->willReturn('/test/path');
    //     $requestState->method('getQueryParams')->willReturn(['param' => 'value']);

    //     $controllerWrapped->setState($requestState);

    //     $this->assertSame($requestState, $controllerWrapped->RequestState);
    //     $this->assertEquals('GET', $controllerWrapped->method);
    //     $this->assertEquals('/test/path', $controllerWrapped->path);
    //     $this->assertEquals(['param' => 'value'], $controllerWrapped->queryParams);
    // }

    // public function testSetParams()
    // {
    //     $controllerWrapped = new ControllerWrapped();
    //     $params = ['id' => 1, 'name' => 'test'];

    //     $controllerWrapped->setParams($params);

    //     $this->assertEquals($params, $controllerWrapped->routeParameters);
    // }

    public function testGetResponse()
    {
        $controllerWrapped = new ControllerWrapped();
        $controllerWrapped->setController([DemoController::class, 'get']);

        $requestState = $this->createMock(RequestState::class);
        $requestState->method('getMethod')->willReturn('GET');
        $requestState->method('getPath')->willReturn('/test/id/1');
        $requestState->method('getQueryParams')->willReturn(['param' => 'value']);

        $controllerWrapped->setState($requestState);
        $controllerWrapped->setParams(['id' => 1]);

        $response = $controllerWrapped->getResponse();

        // $response->getParams();

        $this->assertInstanceOf(ResponseState::class, $response);
    }

    public function testResolveParams()
    {
        $controllerWrapped = new ControllerWrapped();
        $controller = [DemoController::class, 'get'];
        $requestState = $this->createMock(RequestState::class);
        $routeParams = ['id' => 1];

        $params = $controllerWrapped->resolveParams($controller, $requestState, $routeParams);

        $this->assertEquals(['id' => 1], $params);
    }
}