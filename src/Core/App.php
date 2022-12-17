<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function array_keys;
use function array_reduce;
use function call_user_func_array;
use function substr;

class App
{
    /** @var string[] $modules List of module to instanciate */
    private array $modules = [];

    /** @var array<string, mixed> $config Application configuration */
    private array $config = [];

    /**
     * App constructor
     *
     * @param string[] $modules List of modules to load
     */
    public function __construct(
        array $modules = [],
        array $config = []
    ) {
        $this->config = $config;
        if (false === isset($this->config['router'])) {
            throw new Exception('No router defined.');
        }

        foreach ($modules as $module) {
            $this->modules[] = new $module($this->config['router']);
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

        return $this->config['router']->run($request);

        // $route = $this->config['router']->match($request);
        // if (null === $route) {
        //     return new Response(
        //         404,
        //         [],
        //         'Route not found'
        //     );
        // }

        // $matches = $route->getMatches();
        // $request = array_reduce(
        //     array_keys($matches),
        //     function ($request, $key) use ($matches) {
        //         return $request->withAttribute($key, $matches[$key]);
        //     },
        //     $request
        // );
        // $callable = $route->getCallback();

        // return call_user_func_array($callable, [$request]);
    }
}
