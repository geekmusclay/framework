<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Geekmusclay\Framework\Core\Encrypter;
use Geekmusclay\Framework\Handler\WebpackHandler;
use Geekmusclay\Router\Interfaces\RouterInterface;

class TwigExtension extends AbstractExtension
{
    /** RouterInterface $router The application router */
    private RouterInterface $router;

    /** @var Encrypter $encrypter Contain encryption service */
    private Encrypter $encrypter;

    /** @var WebpackHandler $handler Contain webpack scripts and css handler */
    private WebpackHandler $handler;

    /**
     * Undocumented function
     *
     * @param RouterInterface $router    The application router
     * @param Encrypter       $encrypter The encryption service
     */
    public function __construct(
        RouterInterface $router,
        Encrypter $encrypter,
        WebpackHandler $handler
    ) {
        $this->router    = $router;
        $this->encrypter = $encrypter;
        $this->handler   = $handler;
    }

    /**
     * The custom Twig functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'path']),
            new TwigFunction('webpack_handle_js', [$this, 'js']),
            new TwigFunction('webpack_handle_css', [$this, 'css']),
        ];
    }

    /**
     * The custom Twig filters.
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('encrypt', [$this, 'encrypt']),
            new TwigFilter('decrypt', [$this, 'decrypt']),
        ];
    }

    /**
     * Get route url by his name.
     *
     * @param string  $name   The name of the route
     * @param mixed[] $params The params that are passed in the url
     * @throws Exception
     */
    public function path(string $name, array $params = []): string
    {
        return $this->router->path($name, $params);
    }

    /**
     * This function will encrypt the id so he can be shared by url.
     *
     * @param int $id The id to encrypt
     * @return string The encrypted id
     */
    public function encrypt(int $id): string
    {
        return $this->encrypter->encrypt($id);
    }

    /**
     * This function will decrypt the id.
     *
     * @params string $id The encrypted id
     * @return int Return -1 if an error occured, the decryptec id otherwise
     */
    public function decrypt(string $id): int
    {
        return $this->encrypter->decrypt($id);
    }

    /**
     * This function will retrieve the webpack js files given the page name
     *
     * @param string $page The name of the concerned page
     * @return string The script tags
     */
    public function js(string $page): string
    {
        return $this->handler->js($page);
    }

    /**
     * This function will retrieve the webpack css files given the page name
     *
     * @param string $page The name of the concerned page
     * @return string The link tags
     */
    public function css(string $page): string
    {
        return $this->handler->css($page);
    }
}
