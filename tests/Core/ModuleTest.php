<?php

namespace Tests\Core;

use Geekmusclay\DI\Core\Container;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Geekmusclay\Framework\Core\App;
use Geekmusclay\Router\Core\Router;
use Tests\Fake\FakeModule;

class ModuleTest extends TestCase
{
    public function setUp(): void
    {
        $this->container = new Container();
        $this->router = new Router($this->container);
    }

    public function testModule()
    {
        $app = new App([
            FakeModule::class
        ], [
            'router' => $this->router
        ]);
        $request = new ServerRequest('GET', '/fake');
        $response = $app->run($request);
        $this->assertEquals('Hello Fake', (string) $response->getBody());

        $request = new ServerRequest('GET', '/fake/8');
        $response = $app->run($request);
        $this->assertEquals('Welcome on article 8', (string) $response->getBody());
    }
}
