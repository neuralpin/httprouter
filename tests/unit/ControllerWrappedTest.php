<?php

declare(strict_types=1);

use Neuralpin\HTTPRouter\ControllerWrapped;
use Neuralpin\HTTPRouter\Demo\DemoController;
use Neuralpin\HTTPRouter\Exception\InvalidControllerException;
use Neuralpin\HTTPRouter\Interface\RequestState;
use Neuralpin\HTTPRouter\Interface\ResponseState;
use PHPUnit\Framework\TestCase;

class ControllerWrappedTest extends TestCase
{
    public function test_wrap_controller()
    {
        $ControllerWrapped = new ControllerWrapped;

        // Test with a valid callable array
        $ControllerWrapped->wrapController([DemoController::class, 'get']);
        $this->assertIsArray($ControllerWrapped->getUnwrappedController());

        // Test with a valid callable object
        $ControllerWrapped->wrapController(fn () => null);
        $this->assertIsObject($ControllerWrapped->getUnwrappedController());

        // Test with invalid callables
        $this->expectException(InvalidControllerException::class);

        // Object exists but is not callable
        $ControllerWrapped->wrapController([DemoController::class]);

        // Object exists but method does not exists
        $ControllerWrapped->wrapController([DemoController::class, 'invalidMethod']);

        // Object is not reachable
        $ControllerWrapped->wrapController(['BadController', 'invalidMethod']);
    }

    public function test_set_state()
    {
        $requestState = $this->createMock(RequestState::class);
        $requestState->method('getMethod')->willReturn('GET');
        $requestState->method('getPath')->willReturn('/test/path');
        $requestState->method('getQueryParams')->willReturn(['param' => 'value']);

        $controllerWrapped = new ControllerWrapped;
        $controllerWrapped->setState($requestState);
        $this->assertSame($requestState, $controllerWrapped->getState());
    }

    public function test_set_params()
    {
        $controllerWrapped = new ControllerWrapped;
        $params = ['id' => 1, 'name' => 'test'];

        $controllerWrapped->setParameters($params);

        $this->assertEquals($params, $controllerWrapped->getParameters());
    }

    public function test_get_response()
    {
        $requestState = $this->createMock(RequestState::class);
        $requestState->method('getMethod')->willReturn('GET');
        $requestState->method('getPath')->willReturn('/test/id/1');
        $requestState->method('getQueryParams')->willReturn(['param' => 'value']);

        $controllerWrapped = new ControllerWrapped;
        $controllerWrapped->wrapController([DemoController::class, 'get']);
        $controllerWrapped->setState($requestState);
        $controllerWrapped->setParameters(['id' => 1]);

        $response = $controllerWrapped->getResponse();

        $this->assertInstanceOf(ResponseState::class, $response);
    }

    public function test_resolve_params()
    {
        $controllerWrapped = new ControllerWrapped;
        $controller = [DemoController::class, 'get'];
        $requestState = $this->createMock(RequestState::class);
        $routeParams = ['id' => 1];

        $params = $controllerWrapped->resolveParams($controller, $requestState, $routeParams);

        $this->assertEquals(['id' => 1], $params);
    }
}
