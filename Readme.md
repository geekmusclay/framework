# GeekMusclay Framework

## Setup

Create a `.htaccess` file with : 
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L,NC]
```

Create a `.env` file with : 
```
APP_ENV=dev
DATABASE_URL=mysql:host=localhost;dbname=test
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

$app->get('/', function () {
    return new Response(200, [], 'Welcome on my app');
});

$response = $app->run(ServerRequest::fromGlobals());

try {
    $response = $app->run(ServerRequest::fromGlobals());
} catch (Throwable $e) {
    dd($e);
}
```