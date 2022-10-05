<?php

declare(strict_types=1);

namespace Tests\Fake;

use Tests\Fake\Controller\FakeController;
use Geekmusclay\Router\Router;

class FakeModule
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->router->get('/fake', [FakeController::class, 'index'], 'fake.index');
        $this->router->get('/fake/:id', [FakeController::class, 'show'], 'fake.show')->with([':id' => '[0-9]+']);
    }
}
