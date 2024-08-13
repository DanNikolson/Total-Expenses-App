<?php

declare(strict_types=1);

namespace App;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * This class is responsible for handling CSRF failures.
 */
class Csrf
{
    /**
     * @param ResponseFactoryInterface $responseFactory The factory used to create the response.
     */
    public function __construct(private readonly ResponseFactoryInterface $responseFactory) {}

    /**
     * Returns a closure that creates a response with a 403 status code.
     */
    public function failureHandler(): Closure
    {
        return fn(
            ServerRequestInterface $request,
            RequestHandlerInterface $handler
        ) => $this->responseFactory->createResponse()->withStatus(403);;
    }
}
