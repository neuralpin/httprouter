<?php

require __DIR__.'/../vendor/autoload.php';

use Neuralpin\HTTPRouter\Demo\DemoController;
use Neuralpin\HTTPRouter\Response;
use Neuralpin\HTTPRouter\Router;

$Router = new Router;

$Router->any('/', fn () => 'Hello world!');
$Router->any('/home', fn () => Response::template(__DIR__.'/template/home.html'));
$Router->get('/api/v1/product', [DemoController::class, 'list']);
$Router->post('/api/v1/product', [DemoController::class, 'create']);
$Router->get('/api/v1/product/:id', [DemoController::class, 'get']);
$Router->patch('/api/v1/product/:id', [DemoController::class, 'update']);
$Router->delete('/api/v1/product/:id', [DemoController::class, 'delete']);
$Router
    ->get('/api/v1/search/:search', function ($search) {
        $search = explode('/', htmlspecialchars($search));
        $search = implode(' ', $search);

        return "Searching: $search";
    })
    ->ignoreParamSlash();

try {

    /**
     * @var Router $Router
     */
    $Controller = $Router->getController();

} catch (\Throwable|\Exception $Exception) {
    if ($Router->isNotFoundException($Exception)) {
        $Controller = $Router->wrapController(
            fn () => Response::template(__DIR__.'/template/404.html', 404)
        );
    } elseif ($Router->isMethodNotAllowedException($Exception)) {
        $Controller = $Router->wrapController(
            fn () => Response::template(__DIR__.'/template/405.html', 405)
        );
    } else {
        $Controller = $Router->wrapController(
            fn () => Response::template(__DIR__.'/template/500.html', 500)
        );
    }
}

// dd($Router, $Router->getController(), $Router->getController()->getResponse(), $Router->getController()->getResponse()->getBody());
echo $Controller->getResponse();
