<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use GuzzleHttp\Psr7\ServerRequest;
use Geekmusclay\Framework\Core\App;
use function Http\Response\send;

$app = new App();

$response = $app->run(ServerRequest::fromGlobals());

send($response);