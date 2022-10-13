<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use Exception;

use function extract;
use function ob_get_clean;
use function ob_start;

class Renderer
{
    private const DEFAULT_NAMESPACE = 'root';

    /** @var string[] $paths List or registered paths */
    private array $paths = [];

    /**
     * Function to add a namespace and paths.
     * If the namespace is left empty, the function will assume that
     * the given path is the root.
     *
     * @param string      $path      Path to add
     * @param string|null $namespace (OPTIONAL) Namespace related to the path
     * @return self
     */
    public function add(string $path, ?string $namespace = null): self
    {
        if (null === $namespace) {
            $this->paths[self::DEFAULT_NAMESPACE] = $path;
        } else {
            $this->paths[$namespace] = $path;
        }

        return $this;
    }

    /**
     * Will parse the given alias to derive the namspace and file.
     *
     * @param string $alias The alias to be parsed
     * @return array An array containing "namespace" and "file" keys 
     */
    public function parse(string $alias): array
    {
        $res = [];
        $index = strpos($alias, '@');
        if (false !== $index) {
            $res['namespace'] = substr($alias, 0, $index);
            $res['file']      = substr($alias, $index + 1);
        } else {
            $res['namespace'] = self::DEFAULT_NAMESPACE;
            $res['file']      = $alias;
        }

        return $res;
    }

    /**
     * Render function.
     *
     * @param string $alias      Alias of the view, like blog@demo, or simply demo
     * @param array  $parameters The parameters to be passed to the view
     * @return string|false
     */
    public function render(string $alias, array $parameters = [])
    {
        $res = $this->parse($alias);
        if (false === isset($this->paths[$res['namespace']])) {
            throw new Exception('Wrong alias');
        }

        ob_start();

        extract($parameters);

        require $this->paths[$res['namespace']] . DIRECTORY_SEPARATOR . $res['file'] . '.php';

        return ob_get_clean();
    }
}
