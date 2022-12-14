<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Renderer;

use Geekmusclay\Framework\Interfaces\RendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRenderer implements RendererInterface
{
    /** @var FilesystemLoader $loader Twig loader */
    private FilesystemLoader $loader;

    /** @var Environment $env Twig environment */
    private Environment $env;

    /**
     * @param string $path   Default path for Twig
     * @param array  $config Configuration for Twig
     */
    public function __construct(FilesystemLoader $loader, Environment $env)
    {
        $this->loader = $loader;
        $this->env    = $env;
    }

    /**
     * Function to add a namespace and paths.
     * If the namespace is left empty, the function will assume that
     * the given path is the root.
     *
     * @param string $path      Path to add
     * @param string $namespace Namespace related to the path
     */
    public function add(string $path, string $namespace): self
    {
        $this->loader->addPath($path, $namespace);

        return $this;
    }

    /**
     * Registers a Global.
     *
     * @param string $key   The key
     * @param mixed  $value The global value
     */
    public function addGlobal(string $key, $value): self
    {
        $this->env->addGlobal($key, $value);

        return $this;
    }

    /**
     * Render function.
     *
     * @param string $view       View to render
     * @param array  $parameters The parameters to be passed to the view
     * @return string|false
     */
    public function render(string $view, array $parameters = [])
    {
        return $this->env->render($view, $parameters);
    }
}
