# neuralpin/httprouter

## Description:
PHP HTTP Routing System for microservices, serverless and vanilla custom apps

[Github Repo](https://github.com/neuralpin/httprouter)

## How to use

Example usage
```php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/DemoController.php';

use Neuralpin\HTTPRouter\Response;
use Neuralpin\HTTPRouter\Router;

$Router = new Router;

// Static and Dynamic HTML Routes
$Router->any('/', fn () => 'Hello world!');
$Router->get('/page', fn () => Response::template(__DIR__.'/template/hello-page.html'));
$Router->get('/page2', [DemoController::class, 'templating']);

// APi Like Routes
$Router->get('/api/product', [DemoController::class, 'list']);
$Router->post('/api/product', [DemoController::class, 'create']);
$Router->get('/api/product/:id', [DemoController::class, 'get']);
$Router->patch('/api/product/:id', [DemoController::class, 'update']);
$Router->delete('/api/product/:id', [DemoController::class, 'delete']);

// Match whole URI when it starts with /search
$Router->get('/search:search', function ($search) {

    $search = explode('/', htmlspecialchars($search));
    $search = implode(' ', $search);

    return "Searching: $search";
})->ignoreParamSlash();

try {

    $Controller = $Router->getController();

} catch (\Throwable|\Exception $Exception) {
    if ($Router->isNotFoundException($Exception)) {
        $Controller = $Router->wrapController(
            fn () => Response::template(content: __DIR__.'/template/404.html', status: 404)
        );
    } elseif ($Router->isMethodNotAllowedException($Exception)) {
        $Controller = $Router->wrapController(
            fn () => Response::template(content: __DIR__.'/template/405.html', status: 405)
        );
    } else {
        $Controller = $Router->wrapController(
            fn () => Response::template(content: __DIR__.'/template/500.html', status: 500)
        );
    }
}

echo $Controller->getResponse();
```
## Use the module with composer
```bash
composer config repositories.neuralpin/HTTPRouter vcs https://github.com/neuralpin/httprouter
composer require neuralpin/httprouter
```