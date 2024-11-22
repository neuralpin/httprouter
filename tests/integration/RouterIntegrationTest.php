<?php

use PHPUnit\Framework\TestCase;
use Neuralpin\HTTPRouter\Router;
use Neuralpin\HTTPRouter\Response;
use Neuralpin\HTTPRouter\Demo\DemoController;
use Neuralpin\HTTPRouter\Exception\NotFoundException;
use Neuralpin\HTTPRouter\Exception\MethodNotAllowedException;

class RouterIntegrationTest extends TestCase
{
    public function testPlainResponse()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $responseText = 'Hello world!';

        $Router = new Router();
        $Router->get('/', fn() => $responseText);

        $Response = $Router->getController()->getResponse();

        $ExpectedResponse = Response::plain($responseText)->setPath('');

        $this->assertEquals($ExpectedResponse, $Response);
        $this->assertEquals($responseText, $Response->getBody());
    }

    public function testRouteWithParametersAndJsonOutput()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/product/1';

        $Router = new Router();
        $Router->get('/api/product/:id', [DemoController::class, 'get']);

        $Response = $Router->getController()->getResponse();

        $ExpectedResponse = Response::json([
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
            ->setPath($_SERVER['REQUEST_URI']);

        $this->assertEquals($ExpectedResponse, $Response);
    }

    public function testRouteWithForwardSlashAsParameter()
    {
        $searchingFor = 'lorem/ipsum';

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = "/search/$searchingFor";

        $Router = new Router();
        $Router
            ->get('search/:searching', fn($searching) => $searching)
            ->ignoreParamSlash();

        $Response = $Router->getController()->getResponse();

        $ExpectedResponse = Response::plain($searchingFor)
            ->setParams([
                'searching' => $searchingFor,
            ])
            ->setPath($_SERVER['REQUEST_URI']);


        $this->assertEquals($ExpectedResponse, $Response);
    }

    public function testNotFoundException()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/this-page-does-not-exists';

        $Router = new Router();
        $Router->get('/', fn() => null);

        $this->expectException(NotFoundException::class);
        $Response = $Router->getController()->getResponse();
    }

    public function testMethodNotAllowedException()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/';

        $Router = new Router();
        $Router->get('/', fn() => null);

        $this->expectException(MethodNotAllowedException::class);
        $Response = $Router->getController()->getResponse();
    }

}