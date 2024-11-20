# neuralpin/httprouter

## Description:
PHP HTTP Routing System for microservices, serverless and vanilla custom apps

[Github Repo](https://github.com/neuralpin/httprouter)

## How to use

Example usage
```php
require __DIR__.'/../vendor/autoload.php';

use Neuralpin\HTTPRouter\Router;
use Neuralpin\HTTPRouter\Response;
use Neuralpin\HTTPRouter\Demo\DemoController;

$Router = new Router();
$Router->any('/', fn() => 'Hello world!');
$Router->any('/home', fn() => Response::template(__DIR__ . '/template/home.html'));
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
    $Controller = $Router->getController();
} catch (\Exception $Exception) {
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
```
## Use the module with composer
```bash
composer config repositories.neuralpin/HTTPRouter vcs https://github.com/neuralpin/httprouter
composer require neuralpin/httprouter
```