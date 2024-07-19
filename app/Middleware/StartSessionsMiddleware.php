<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\SessionException;
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException('Session has already been started');
        }

        if (headers_sent($fileName, $line)) {
            throw new SessionException('Headers have already been sent');
        }

        session_start();

        $response = $handler->handle($request);

        return $response;

        session_write_close();
    }
}
