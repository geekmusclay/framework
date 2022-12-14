<?php 

namespace Tests\Core;

use Geekmusclay\Framework\Renderer\PHPRenderer;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    private PHPRenderer $renderer;

    public function setUp(): void
    {
        $this->renderer = new PHPRenderer();
    }

    public function testRenderPath()
    {
        $this->renderer->add(__DIR__ . '/views', 'blog');
        $content = $this->renderer->render('blog@demo');
        $this->assertEquals('Salut les gens', $content);
    }

    public function testRenderDefaultPath()
    {
        $this->renderer->add(__DIR__ . '/views');
        $content = $this->renderer->render('demo');
        $this->assertEquals('Salut les gens', $content);
    }

    public function testRenderWithParameters()
    {
        $this->renderer->add(__DIR__ . '/views');
        $content = $this->renderer->render('article', [
            'id' => 3
        ]);
        $this->assertEquals('Bienvenue sur l\'article 3', $content);

        $this->renderer->add(__DIR__ . '/views', 'blog');
        $content = $this->renderer->render('blog@article', [
            'id' => 3
        ]);
        $this->assertEquals('Bienvenue sur l\'article 3', $content);
    }
}