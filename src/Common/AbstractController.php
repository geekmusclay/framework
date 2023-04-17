<?php

declare(strict_types=1);

namespace Geekmusclay\Framework\Common;

use Geekmusclay\Framework\Interfaces\RendererInterface;
use Geekmusclay\Router\Core\JsonResponse;
use Geekmusclay\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractController
{
    const JSON_HEADERS = [
        'Content-Type' => 'application/json'
    ];

    /** @var RendererInterface $renderer Defdault renderer */
    protected RendererInterface $renderer;

    /** @var RouterInterface $router Application router */
    protected RouterInterface $router;

    /**
     * Controller constructor
     *
     * @param RendererInterface $renderer Defdault renderer
     * @param RouterInterface   $router   Application router
     */
    public function __construct(RendererInterface $renderer, RouterInterface $router)
    {
        $this->renderer = $renderer;
        $this->router   = $router;
    }

    /**
     * Redirection to a named route.
     *
     * @param ServerRequestInterface $request The server request
     * @param string                 $name    The name of the route to execute
     * @return mixed
     */
    protected function redirect(ServerRequestInterface $request, string $name): mixed
    {
        return $this->router->redirect($request, $name);
    }

    /**
     * Render given file.
     *
     * @param string $path    Path to the file
     * @param array  $params  Params to pass to the view
     * @param array  $headers Headers of the response
     * @return Response
     */
    protected function render(
        string $path,
        array $params  = [],
        array $headers = []
    ): Response {
        $body = $this->renderer->render($path, $params);

        return new Response(200, $headers, $body);
    }

    /**
     * Return a json response for api call.
     *
     * @param array       $body    The body of the response
     * @param integer     $status  The response status code
     * @param array       $headers The response headers
     * @param string      $version Protocol version
     * @param string|null $reason  Reason phrase (when empty a default will be used based on the status code)
     * @return JsonResponse
     */
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
