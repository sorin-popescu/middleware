<?php
declare(strict_types = 1);

namespace Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\SapiEmitter;

class DiactorosResponderMiddleware implements MiddlewareInterface
{
    /**
     * @var EmitterInterface
     */
    protected $emitter;

    /**
     * @var bool
     */
    protected $checkOutputStart;

    public function __construct(EmitterInterface $emitter = null, bool $checkOutputStart = false)
    {
        $this->emitter = $emitter ?? new SapiEmitter();
        $this->checkOutputStart = $checkOutputStart;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($this->checkOutputStart === false || headers_sent() === false) {
            $this->emitter->emit($response);
        }

        return $response;
    }
}
