<?php 

declare(strict_types=1);

namespace Geekmusclay\Framework\Interfaces;

interface RendererInterface
{
    /**
     * Function to add a namespace and paths.
     * If the namespace is left empty, the function will assume that
     * the given path is the root.
     *
     * @param string $path      Path to add
     * @param string $namespace Namespace related to the path
     */
    public function add(string $path, string $namespace): self;

    /**
     * Render function.
     *
     * @param string $alias      Alias of the view, like blog@demo, or simply demo
     * @param array  $parameters The parameters to be passed to the view
     * @return string|false
     */
    public function render(string $alias, array $parameters = []);
}
