<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use Geekmusclay\DI\Core\Container;
use Geekmusclay\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function substr;

class App
{
    private Container $container;

    private RouterInterface $router;

    /** @var string[] $modules List of module to instanciate */
    private array $modules = [];

    /**
     * App constructor
     *
     * @param string[] $modules List of modules to load
     */
    public function __construct(
        Container $container,
        RouterInterface $router,
        array $modules = []
    ) {
        $this->container = $container;
        $this->router = $router;

        foreach ($modules as $module) {
            $this->modules[] = $this->container->get($module);
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

        return $this->router->run($request);
    }
}
