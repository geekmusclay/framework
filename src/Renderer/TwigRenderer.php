<?php 

declare(strict_types=1);

namespace Geekmusclay\Framework\Renderer;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Geekmusclay\Framework\Interfaces\RendererInterface;

class TwigRenderer implements RendererInterface
{
    private FilesystemLoader $loader;

    private Environment $twig;

    public function __construct(string $path, array $config = [])
    {
        $this->loader = new FilesystemLoader($path);
        $this->twig = new Environment($this->loader, $config);
    }

    /**
     * Function to add a namespace and paths.
     * If the namespace is left empty, the function will assume that
     * the given path is the root.
     *
     * @param string      $path      Path to add
     * @param string|null $namespace (OPTIONAL) Namespace related to the path
     */
    public function add(string $path, ?string $namespace = null): self
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
        $this->twig->addGlobal($key, $value);

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
        return $this->twig->render($view, $parameters);
    }
}