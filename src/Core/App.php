<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use Geekmusclay\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function array_keys;
use function array_reduce;
use function call_user_func_array;
use function substr;

class App
{
    /** @var array<object|string> $modules List of module to instanciate */
    private array $modules = [];

    /** @var Router $router Application router */
    private Router $router;

    /**
     * App constructor
     *
     * @param string[] $modules List of modules to load
     */
    public function __construct(array $modules = [])
    {
        $this->router = new Router();
        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router);
        }
    }

    /**
     * Application run function
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if (false === empty($uri) && $uri[-1] === '/') {
            return new Response(
                301,
                [
                    'Location' => substr($uri, 0, -1),
                ]
            );
        }

        $route = $this->router->match($request);
        if (null === $route) {
            return new Response(
                404,
                [],
                'Route not found'
            );
        }

        $matches  = $route->getMatches();
        $request  = array_reduce(
            array_keys($matches),
            function ($request, $key) use ($matches) {
                return $request->withAttribute($key, $matches[$key]);
            },
            $request
        );
        $callable = $route->getCallback();

        return call_user_func_array($callable, [$request]);
    }
}
