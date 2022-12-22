<?php 

declare(strict_types=1);

namespace Geekmusclay\Framework\Factory;

use Geekmusclay\Framework\Renderer\TwigRenderer;
use Geekmusclay\Framework\Twig\TwigExtension;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{
    public function __invoke(string $path, array $config = [], ContainerInterface $container): TwigRenderer
    {
        $this->loader = new FilesystemLoader($path);
        $this->twig   = new Environment($this->loader, $config);
        $this->twig->addExtension($container->get(TwigExtension::class));

        return new TwigRenderer($this->loader, $this->twig);
    }
}
