# neuralpin/httprouter

## Description:
Helper for http request processing
[Github Repo](https://github.com/neuralpin/httprouter)

## How to use

Example for basic usage
```php
use Neuralpin\HTTPRouter\Router;
use Controllers\UserController;
use Controllers\DefaultController;

$DefaultController = new DefaultController();

Router::get( '/', function(){
    echo "Hello world";
    return true;
} );
Router::get( 'user/login', UserController::class, 'loginForm' );
Router::post( 'user/login', UserController::class, 'loginUser' );
Router::get( 'user/register', UserController::class, 'registerForm' );
Router::post( 'user/register', UserController::class, 'post' );
Router::get( 'user/:id', UserController::class, 'get' );
Router::patch( 'user/:id', UserController::class, 'patch' );
Router::delete( 'user/:id', UserController::class, 'delete' );

if ( !Router::match() ) {
    header('HTTP/1.0 404 Not Found');
    require __DIR__.'/404.html';
}
```
## Use the module with composer
```bash
composer config repositories.neuralpin/HTTPRouter vcs https://github.com/neuralpin/httprouter
composer require neuralpin/HTTPRouter
```