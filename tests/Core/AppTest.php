<?php 

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\ServerRequest;
use Geekmusclay\Framework\Core\App;

class AppTest extends TestCase
{
    private App $app;

    public function setUp(): void
    {
        $this->app = new App();
    }

    public function testRedirectTrailingSlash()
    {
        $request = new ServerRequest('GET', '/testslash/');
        /** @var Response $response */
        $response = $this->app->run($request);

        $this->assertContains('/testslash', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }
}