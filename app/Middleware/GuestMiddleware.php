<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that checks if a user is not authenticated.
 * If the user is authenticated, it redirects to the home page.
 */
class GuestMiddleware implements MiddlewareInterface
{
    /**
     * Create a new instance of GuestMiddleware.
     *
     * @param ResponseFactoryInterface $responseFactory The factory used to create the redirect response.
     */
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SessionInterface $session
    ) {
    }

    /**
     * Process the request and handle the authentication.
     * If the user is authenticated, it redirects to the home page.
     *
     * @param ServerRequestInterface $request The request object.
     * @param RequestHandlerInterface $handler The request handler.
     * @return ResponseInterface The response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->session->get('user')) {
            return $this->responseFactory->createResponse(302)->withHeader('Location', '/');
        }

        return $handler->handle($request);
    }
}
