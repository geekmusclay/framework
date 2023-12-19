<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Handler;

use Psr\Container\ContainerInterface;

class WebpackHandler
{
    /** @var ContainerInterface $container Dependency injection container */
    private ContainerInterface $container;

    /**
     * Webpack handler constructor
     *
     * @param ContainerInterface $container Dependency injection container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * This function will retrieve and build the Webpack javascripts path
     *
     * @param string  $page     The name of the targted page
     * @param boolean $withTags Do we add path into HTML tags ?
     * @return string|null
     */
    public function js(string $page, bool $withTags = true): ?string
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

        if (false === $withTags) {
            return $content['entrypoints'][$page]['js'];
        }

        $scripts = array_map(function ($script) {
            return "<script src='$script'></script>";
        }, $content['entrypoints'][$page]['js']);

        return implode('', $scripts);
    }

    /**
     * This function will retrieve and build the Webpack css path
     *
     * @param string  $page     The name of the targted page
     * @param boolean $withTags Do we add path into HTML tags ?
     * @return string|null
     */
    public function css(string $page, bool $withTags = true): ?string
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

        if (false === $withTags) {
            return $content['entrypoints'][$page]['css'];
        }

        $scripts = array_map(function ($script) {
            return "<link href='$script' rel='stylesheet'>";
        }, $content['entrypoints'][$page]['css']);

        return implode('', $scripts);
    }
}
