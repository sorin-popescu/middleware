<?php

use FastRoute\RouteCollector;
use League\Plates\Engine;
use Middlewares\Action\HomeAction;
use Middlewares\DiactorosResponderMiddleware;
use Middlewares\Utils\Dispatcher;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

require_once __DIR__ . '/../vendor/autoload.php';


$container = new Container();

$container[Engine::class] = function ($c) {
    return new Engine(__DIR__ . '/../src/templates/', 'phtml');
};
$container[HomeAction::class] = function ($c) {
    return new HomeAction($c[Engine::class]);
};

$psr11 = new Psr11Container($container);

$routeDispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $r) use ($psr11) {
    $r->addRoute('GET', '/hello/{name}', $psr11->get(HomeAction::class));
});


$dispatcher = new Dispatcher([
    new DiactorosResponderMiddleware(new SapiEmitter()),
    new Middlewares\Uuid(),
    new Middlewares\FastRoute($routeDispatcher),
    new Middlewares\RequestHandler($psr11),
]);

$response = $dispatcher->dispatch(ServerRequestFactory::fromGlobals());


