<?php

declare(strict_types=1);

namespace Tests\Fake\Controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FakeController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'Hello Fake');
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'Welcome on article ' . $request->getAttribute('id'));
    }
}