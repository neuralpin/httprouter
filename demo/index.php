<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/config/routes.php';

use Neuralpin\HTTPRouter\Response;

try {

    $Controller = $Router->getController();

} catch (\Throwable| \Exception $Exception) {
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

echo $Controller->getResponse();
