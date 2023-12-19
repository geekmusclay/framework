<?php

declare(strict_types=1);

namespace Geekmusclay\Framework;

use Psr\Container\ContainerInterface;

class WebpackHandler
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function js(string $page): ?string
    {
        $path = $this->container->get('wabpack.entry');
        $content = file_get_contents($path);
        if (false === $content) {
            return null;
        }

        $content = json_decode($content, true);
        if (false === isset($content['entrypoints'][$page]['js'])) {
            return null;
        }

        $scripts = array_map(function ($script) {
            return "<script src='$script'></script>";
        }, $content['entrypoints'][$page]['js']);

        return implode('', $scripts);
    }

    public function css(string $page): ?string
    {
        $path = $this->container->get('wabpack.entry');
        $content = file_get_contents($path);
        if (false === $content) {
            return null;
        }

        $content = json_decode($content, true);
        if (false === isset($content['entrypoints'][$page]['css'])) {
            return null;
        }

        $scripts = array_map(function ($script) {
            return "<link href='$script' rel='stylesheet'>";
        }, $content['entrypoints'][$page]['css']);

        return implode('', $scripts);
    }
}
