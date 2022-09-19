<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Core;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function substr;

class App
{
    /**
     * Undocumented function
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if (false === empty($uri) && $uri[-1] === '/') {
            return new Response(
                301,
                [
                    'Location' => substr($uri, 0, -1),
                ]
            );
        }

        return new Response(200, [], 'Hello World !');
    }
}
