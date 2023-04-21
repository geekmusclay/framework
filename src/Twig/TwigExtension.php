<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Twig;

use Geekmusclay\Framework\Core\Encrypter;
use Geekmusclay\Router\Interfaces\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    /** RouterInterface $router The application router */
    private RouterInterface $router;

    /** @var Encrypter $encrypter Contain encryption service */
    private Encrypter $encrypter;

    /**
     * Undocumented function
     *
     * @param RouterInterface $router    The application router
     * @param Encrypter       $encrypter The encryption service
     */
    public function __construct(RouterInterface $router, Encrypter $encrypter)
    {
        $this->router    = $router;
        $this->encrypter = $encrypter;
    }

    /**
     * The custom Twig functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'path']),
        ];
    }

    /**
     * The custom Twig filters.
     */
    public function getFilters()
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
     * @throws Exception Throw exception when route does not exist
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
}
