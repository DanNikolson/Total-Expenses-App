<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that starts the session, saves previous URL,
 * and saves the session.
 */
class StartSessionsMiddleware implements MiddlewareInterface
{
    /**
     * Create a new instance of StartSessionsMiddleware.
     *
     * @param SessionInterface $session The session interface.
     */
    public function __construct(private readonly SessionInterface $session)
    {
    }

    /**
     * Process the request and handle the session.
     *
     * @param ServerRequestInterface $request The request object.
     * @param RequestHandlerInterface $handler The request handler.
     * @return ResponseInterface The response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Start the session
        $this->session->start();

        // Continue processing the request
        $response = $handler->handle($request);

        // Save previous URL
        if ($request->getMethod() === 'GET') {
            $this->session->put('previousUrl', (string) $request->getUri());
        }

        // Save the session
        $this->session->save();

        return $response;
    }
}
