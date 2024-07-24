<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @description This middleware will start a session if it has not already been started. It will throw an
 * exception if it has already been started. If headers have already been sent, it will throw an exception.
 *
 * @note This middleware will throw an exception if a session has already been started.
 * @note This middleware will throw an exception if headers have already been sent.
 *
 */
class StartSessionsMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly SessionInterface $session)
    {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->start();

        $response = $handler->handle($request);

        $this->session->save();

        return $response;
    }
}
