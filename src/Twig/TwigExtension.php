<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Geekmusclay\Router\Interfaces\RouterInterface;

class TwigExtension extends AbstractExtension
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'path']),
        ];
    }

    public function path(string $name, array $params): string
    {
        return $this->router->path($name, $params);
    }
}
