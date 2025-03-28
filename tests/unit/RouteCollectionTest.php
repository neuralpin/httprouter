<?php

declare(strict_types=1);

use Neuralpin\HTTPRouter\Interface\ControllerMapper;
use Neuralpin\HTTPRouter\Route;
use Neuralpin\HTTPRouter\RouteCollection;
use PHPUnit\Framework\TestCase;

class RouteCollectionTest extends TestCase
{
    public function test_set_controller_mapper()
    {
        $routeCollection = new RouteCollection;
        $routeCollection->setControllerMapper(ControllerMapper::class);

        $this->assertEquals(ControllerMapper::class, $routeCollection->getControllerMapper());
    }

    public function test_get_routes()
    {
        $routeCollection = new RouteCollection;
        $this->assertIsArray($routeCollection->getRoutes());
        $this->assertEmpty($routeCollection->getRoutes());
    }

    public function test_add_route()
    {
        $routeCollection = new RouteCollection;
        $routeCollection->setControllerMapper(Route::class);

        $callable = function () {
            return 'response';
        };

        $route = $routeCollection->addRoute('GET', 'test/path', $callable);

        $this->assertInstanceOf(ControllerMapper::class, $route);
        $this->assertArrayHasKey('test/path', $routeCollection->getRoutes());
        $this->assertSame($route, $routeCollection->getRoutes()['test/path']);
    }
}
