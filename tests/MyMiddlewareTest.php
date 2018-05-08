<?php

namespace Middlewares\Tests;

use Middlewares\MyMiddleware;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use PHPUnit\Framework\TestCase;

class MyMiddlewareTest extends TestCase
{
    public function testMyMiddleware()
    {
        $request = Factory::createServerRequest();

        $response = Dispatcher::run([
            new MyMiddleware(),
        ], $request);

        $this->assertSame(200, $response->getStatusCode());
    }
}
