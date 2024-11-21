<?php

use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\Router;
use Neuralpin\HTTPRouter\Response;
use Neuralpin\HTTPRouter\Demo\DemoController;

class RouterIntegrationTest extends TestCase
{
    public function testPlainResponse()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $responseText = 'Hello world!';

        $Router = new Router();
        $Router->get('/', fn() => $responseText);

        // $Controller = $Router->getController();
        // $Response = $Controller->getResponse();

        $Response = $Router->getController()->getResponse();

        $ResponseTest = Response::plain($responseText)->setPath('');

        $this->assertEquals($ResponseTest, $Response);
        $this->assertEquals($responseText, $Response->getBody());
    }

    public function testRouteWithParametersAndJsonOutput()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/product/1';

        $Router = new Router();
        $Router->get('/api/product/:id', [DemoController::class, 'get']);

        $Response = $Router->getController()->getResponse();

        $ResponseTest = Response::json([
                'id' => 1,
                'data' => [
                    'id' => 1,
                    'title' => 'Lorem ipsum',
                    'price' => 123,
                ],
            ])
            ->setParams([
                'id' => 1,
            ])
            ->setPath('api/product/1');

        $this->assertEquals($ResponseTest, $Response);
    }

    // public function testRouteWithForwardSlashAsParameter()
    // {
    //     $_SERVER['REQUEST_METHOD'] = 'GET';
    //     $_SERVER['REQUEST_URI'] = '/search/product/1';

    //     $Router = new Router();
    //     $Router
    //         ->get('/api/product/:id', [DemoController::class, 'get']);

    //     $Response = $Router->getController()->getResponse();

    //     $ResponseTest = Response::json([
    //         'id' => 1,
    //         'data' => [
    //             'id' => 1,
    //             'title' => 'Lorem ipsum',
    //             'price' => 123,
    //         ],
    //     ])
    //         ->setParams([
    //             'id' => 1,
    //         ])
    //         ->setPath('api/product/1');

    //     $this->assertEquals($ResponseTest, $Response);
    // }

}