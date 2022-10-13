<?php 

namespace Tests\Core;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Geekmusclay\Framework\Core\DotEnv;

class DotEnvTest extends TestCase
{
    public function testEnv()
    {
        $this->assertEquals(getenv('TEST_VALUE'), false);
        $path = __DIR__ . '/../Fake/.env';
        DotEnv::load($path);
        $this->assertEquals(getenv('TEST_VALUE'), 'hello_world');
    }

    public function testInvalidEnv()
    {
        $this->expectException(InvalidArgumentException::class);
        $path = __DIR__ . '/../wrong/path/.env';
        DotEnv::load($path);
    }
}