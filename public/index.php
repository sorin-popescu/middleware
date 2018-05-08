<?php

use Middlewares\DiactorosResponderMiddleware;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$request = Factory::createServerRequest();

$routeDispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/hello/{name}', function ($request) {
        $response = (new \Zend\Diactoros\Response());
        $response->getBody()->write(json_encode(['app' => 'ok']));
        return $response;
    });
});


$dispatcher = new Dispatcher([
    new DiactorosResponderMiddleware(new SapiEmitter()),
    new Middlewares\FastRoute($routeDispatcher),
    new Middlewares\Uuid(),
    new Middlewares\RequestHandler(),
]);

$response = $dispatcher->dispatch(ServerRequestFactory::fromGlobals());


