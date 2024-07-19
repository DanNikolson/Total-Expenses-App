<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * @description Middleware that handles ValidationExceptions and redirects to /register.
 */
class ValidationExceptionMiddleware implements MiddlewareInterface
{
    /**
     * The factory used to create the redirect response.
     *
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * Create a new instance of ValidationExceptionMiddleware.
     *
     * @param ResponseFactoryInterface $responseFactory The factory used to create the redirect response.
     */
    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Process the request and handle any ValidationExceptions that occur.
     *
     * If a ValidationException is thrown, a redirect to the referer is returned.
     *
     * @param  ServerRequestInterface  $request     The request object.
     * @param  RequestHandlerInterface $handler     The request handler.
     * @return ResponseInterface                     The response.
     * @throws ValidationException                  If a ValidationException is thrown.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = $this->responseFactory->createResponse();

            $referer = $request->getServerParams()['HTTP_REFERER'];

            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }
}
