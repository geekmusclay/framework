<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Common;

use Geekmusclay\Framework\Interfaces\RendererInterface;
use GuzzleHttp\Psr7\Response;

abstract class AbstractController
{
    protected RendererInterface $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    protected function render(
        string $path,
        array $params  = [],
        array $headers = []
    ): Response {
        $body = $this->renderer->render($path, $params);

        return new Response(200, $headers, $body);
    }
}
