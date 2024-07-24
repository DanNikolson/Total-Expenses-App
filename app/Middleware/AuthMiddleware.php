<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Contracts\AuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * Middleware that checks if a user is authenticated.
 * If the user is not authenticated, it redirects to the login page.
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Create a new instance of AuthMiddleware.
     *
     * @param ResponseFactoryInterface $responseFactory The factory used to create the redirect response.
     */
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthInterface $auth
    ) {
    }


    /**
     * Process the request and handle the authentication.
     * If the user is authenticated, it will add the user to the request attributes and pass the request
     * to the next middleware.
     * If the user is not authenticated, it will create a redirect response to the login page.
     *
     * @param ServerRequestInterface $request The request object.
     * @param RequestHandlerInterface $handler The request handler.
     * @return ResponseInterface The response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($user = $this->auth->user()) {
            return $handler->handle($request->withAttribute('user', $user));
        }
        return $this->responseFactory->createResponse(302)->withHeader('Location', '/login');
    }
}
