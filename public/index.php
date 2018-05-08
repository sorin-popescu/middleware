<?php

use Middlewares\DiactorosResponderMiddleware;
use Middlewares\Utils\Dispatcher;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

require_once __DIR__ . '/../vendor/autoload.php';


//$container = new \Pimple\Container();
//
//$container[\League\Plates\Engine::class] = function ($c) {
//        new \League\Plates\Engine();
//    };
//$container[\Middlewares\Action\HomeAction::class] = function ($c) {
//        return new \Middlewares\Action\HomeAction($c[\League\Plates\Engine::class]);
//    };
//
//$psr11 = new \Pimple\Psr11\Container($container);

$routeDispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/hello/{name}', \Middlewares\Action\HomeAction::class);
});


$dispatcher = new Dispatcher([
    new DiactorosResponderMiddleware(new SapiEmitter()),
    new Middlewares\Uuid(),
    new Middlewares\FastRoute($routeDispatcher),
    new Middlewares\RequestHandler($psr11),
]);

$response = $dispatcher->dispatch(ServerRequestFactory::fromGlobals());


