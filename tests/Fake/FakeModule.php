<?php

declare(strict_types=1);

namespace Tests\Fake;

use Geekmusclay\Router\Interfaces\RouterInterface;
use Tests\Fake\Controller\FakeController;

class FakeModule
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->router->get('/fake', [FakeController::class, 'index'], 'fake.index');
        $this->router->get('/fake/:id', [FakeController::class, 'show'], 'fake.show')->with(['id' => '[0-9]+']);
    }
}
