<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Common;

use Geekmusclay\Framework\Interfaces\RendererInterface;
use Geekmusclay\Router\Core\JsonResponse;
use GuzzleHttp\Psr7\Response;

abstract class AbstractController
{
    const JSON_HEADERS = [
        'Content-Type' => 'application/json'
    ];

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

    protected function json(
        array $body = [],
        int $status = 200,
        array $headers = [],
        string $version = '1.1',
        ?string $reason = null
    ): JsonResponse {
        $data = json_encode($body);
        $headers = array_merge(self::JSON_HEADERS, $headers);
        $headers = array_unique($headers);

        return new JsonResponse($status, $headers, $data, $version, $reason);
    }
}
