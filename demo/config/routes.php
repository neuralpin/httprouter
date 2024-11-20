<?php

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

$Router->get('/api/v1/search/:search', function ($search) {
    $search = explode('/', htmlspecialchars($search));
    $search = implode(' ', $search);

    return "Searching: $search";
})->ignoreParamSlash();
