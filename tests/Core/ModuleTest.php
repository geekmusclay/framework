<?php

namespace Tests\Core;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Geekmusclay\Framework\Core\App;
use Tests\Fake\FakeModule;

class ModuleTest extends TestCase
{
    public function testModule()
    {
        $app = new App([
            FakeModule::class
        ]);
        $request = new ServerRequest('GET', '/fake');
        $response = $app->run($request);
        $this->assertEquals('Hello Fake', (string) $response->getBody());

        $request = new ServerRequest('GET', '/fake/8');
        $response = $app->run($request);
        $this->assertEquals('Welcome on article 8', (string) $response->getBody());
    }
}
