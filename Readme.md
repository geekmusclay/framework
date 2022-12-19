# GeekMusclay Framework

## Setup

Create a `.htaccess` file with : 
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

Create a `.env` file with : 
```
DATABASE_URL=mysql:host=localhost;dbname=test_api
DATABASE_USER=root
DATABASE_PASSWORD=
```

In an `index.php` file : 
```php
use Geekmusclay\DI\Core\Container;
use Modules\Blog\BlogModule;
use GuzzleHttp\Psr7\ServerRequest;
use Geekmusclay\Framework\Core\App;
use Geekmusclay\Framework\Core\DotEnv;
use Geekmusclay\Router\Core\Router;
use Geekmusclay\Router\Interfaces\RouterInterface;

use function Http\Response\send;

$env = getenv('APP_ENV');
$path = __DIR__ . '/.env';
if ((false === $env || $env === 'dev') && is_file($path)) {
    // Loading environement variables
    DotEnv::load($path);
}

// Instanciate application DI Container
$container = new Container();

// Register application router into container
$router = $container->get(Router::class, [$container]);
// Register our router as RouterInterface for injections
$container->set(RouterInterface::class, $router);

$app = new App($container, [
    BlogModule::class
]);

$response = $app->run(ServerRequest::fromGlobals());

send($response);
```