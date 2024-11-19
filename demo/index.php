<?php

use Neuralpin\HTTPRouter\{
    Router,
    Response,
    ControllerWrapped,
    RouteCollection,
    Exception\NotFoundException,
    Helper\RequestData,
    Demo\DemoController,
};

RouteCollection::any('/', fn() => 'Hello world!');
RouteCollection::any('/home', fn() => Response::template('template/home.html'));
RouteCollection::get('/api/v1/product', [DemoController::class, 'list']);
RouteCollection::post('/api/v1/product', [DemoController::class, 'create']);
RouteCollection::get('/api/v1/product/:id', [DemoController::class, 'get']);
RouteCollection::patch('/api/v1/product/:id', [DemoController::class, 'update']);
RouteCollection::delete('/api/v1/product/:id', [DemoController::class, 'delete']);

RouteCollection::get('/api/v1/search/:search', function ($search) {
    $search = explode('/', htmlspecialchars($search));
    $search = implode(' ', $search);
    return "Searching: $search";
})->ignoreParamSlash();


$Router = new Router;
$RouteCollection = new RouteCollection;
$RequestState = RequestData::createFromGlobals();

try {

    $Controller = $Router->getController($RouteCollection, $RequestState);

    if (is_null($Controller)) {
        throw new NotFoundException; // Throws 404 error when route doesn't exists
    }

} catch (\Exception $Exception) {
    if ($Exception instanceof NotFoundException) {
        $Controller = new ControllerWrapped(
            fn() => Response::template('template/404.html', 404),
            $RequestState,
        );
    } elseif ($Exception instanceof MethodNotAllowedException) {
        $Controller = new ControllerWrapped(
            fn() => Response::template('template/405.html', 405),
            $RequestState,
        );
    } else {
        $Controller = new ControllerWrapped(
            fn() => Response::template('template/500.html', 500),
            $RequestState,
        );
    }
}

echo $Controller->getResponse();