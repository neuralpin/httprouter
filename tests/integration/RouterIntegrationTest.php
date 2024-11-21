<?php

use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\Router;
use Neuralpin\HTTPRouter\Response;
use Neuralpin\HTTPRouter\Demo\DemoController;

class RouterIntegrationTest extends TestCase
{
    public function testGetProduct()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/product/1';

        $Router = new Router();
        $Router->get('/api/product/:id', [DemoController::class, 'get']);

        $Controller = $Router->getController();
        $Response = $Controller->getResponse();

        $ResponseTest = Response::json([
            'id' => 1,
            'data' => [
                'id' => 1,
                'title' => 'Lorem ipsum',
                'price' => 123,
            ],
        ]);
        $ResponseTest->setParams([
            'id' => 1,
        ]);
        $ResponseTest->setPath('api/product/1');

        $this->assertEquals($ResponseTest, $Response);
    }

}