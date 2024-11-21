<?php

use Neuralpin\HTTPRouter\Route;
use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\RouteCollection;
use Neuralpin\HTTPRouter\Interface\ControllerMapper;

class RouteCollectionTest extends TestCase
{
    public function testSetControllerMapper()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->setControllerMapper(ControllerMapper::class);

        $this->assertEquals(ControllerMapper::class, $routeCollection->ControllerMapper);
    }

    public function testGetRoutes()
    {
        $routeCollection = new RouteCollection();
        $this->assertIsArray($routeCollection->getRoutes());
        $this->assertEmpty($routeCollection->getRoutes());
    }

    public function testAddRoute()
    {
        $routeCollection = new RouteCollection();
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