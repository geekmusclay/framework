<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use Geekmusclay\DI\Core\Container;
use Geekmusclay\Router\Interfaces\RouterInterface;
use Geekmusclay\Router\Proxies\RouterProxy;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /** @var ContainerInterface $container Application DI container */
    private Container $container;

    /** @var RouterInterface $router Application router */
    private RouterInterface $router;

    /** @var string[] $modules List of module to instanciate */
    private array $modules = [];

    /**
     * App constructor
     *
     * @param Container $container Application dependency injection container
     * @param string[]  $modules   List of modules to load
     */
    public function __construct(
        Container $container,
        array $modules = []
    ) {
        $this->container = $container;
        $this->router    = $this->container->get(RouterInterface::class);

        foreach ($modules as $module) {
            $this->modules[] = $this->container->get($module);
        }
    }

    /**
     * Router GET function, create a route
     *
     * @param string            $path     Path of the route
     * @param string[]|callable $callable Callable to execute on route match
     * @param string|null       $name     Name of the route
     */
    public function get(string $path, $callable, ?string $name = null): self
    {
        $this->router->get($path, $callable, $name);

        return $this;
    }

    /**
     * Router POST function, create a route
     *
     * @param string            $path     Path of the route
     * @param string[]|callable $callable Callable to execute on route match
     * @param string|null       $name     Name of the route
     */
    public function post(string $path, $callable, ?string $name = null): self
    {
        $this->router->post($path, $callable, $name);

        return $this;
    }

    /**
     * Router PUT function, create a route
     *
     * @param string            $path     Path of the route
     * @param string[]|callable $callable Callable to execute on route match
     * @param string|null       $name     Name of the route
     */
    public function put(string $path, $callable, ?string $name = null): self
    {
        $this->router->put($path, $callable, $name);

        return $this;
    }

    /**
     * Router PATCH function, create a route
     *
     * @param string            $path     Path of the route
     * @param string[]|callable $callable Callable to execute on route match
     * @param string|null       $name     Name of the route
     */
    public function patch(string $path, $callable, ?string $name = null): self
    {
        $this->router->patch($path, $callable, $name);

        return $this;
    }

    /**
     * Router DELETE function, create a route
     *
     * @param string            $path     Path of the route
     * @param string[]|callable $callable Callable to execute on route match
     * @param string|null       $name     Name of the route
     */
    public function delete(string $path, $callable, ?string $name = null): self
    {
        $this->router->delete($path, $callable, $name);

        return $this;
    }

    /**
     * Will allow routes to be declared in a group, using a suffix
     *
     * @param  string   $suffix   Group suffix
     * @param  callable $callable Callable to execute (contains routes declaration)
     * @return mixed
     */
    public function group(string $suffix, callable $callable)
    {
        return $callable(new RouterProxy($suffix, $this->router));
    }

    /**
     * Registers a class / controller containing routes declared
     * using the "Route" attribute
     *
     * @param string $class The class to register
     */
    public function register(string $class): void
    {
        $this->router->register($class);
    }

    /**
     * Starts the "register" function on all files that will be found in the given folder,
     * as well as in all subfolders.
     *
     * @param  string $path      The path to the root directory
     * @param  string $namespace The root namespace
     * @return array             Return an array of result for the needs of the recursive feature
     */
    public function registerDir(string $path, string $namespace): array
    {
        return $this->router->registerDir($path, $namespace);
    }

    /**
     * Application run function
     *
     * @param ServerRequestInterface $request The request to be processed
     * @return ResponseInterface The response to send
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        // $uri = $request->getUri()->getPath();
        // if (false === empty($uri) && $uri[-1] === '/') {
        //     return new Response(
        //         301,
        //         [
        //             'Location' => substr($uri, 0, -1),
        //         ]
        //     );
        // }

        return $this->router->run($request);
    }
}
