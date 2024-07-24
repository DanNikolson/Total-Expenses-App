<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\RequestService;
use App\Contracts\SessionInterface;
use App\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * @description Middleware that handles ValidationExceptions and redirects to the referer.
 */
class ValidationExceptionMiddleware implements MiddlewareInterface
{
    /**
     * Create a new instance of ValidationExceptionMiddleware.
     *
     * @param ResponseFactoryInterface $responseFactory The factory used to create the redirect response.
     * @param SessionInterface $session Gives access to session methods
     */
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SessionInterface $session,
        private readonly RequestService $requestService
    ) {
    }
    /**
     * Process the request and handle any ValidationExceptions that occur.
     * If a ValidationException is thrown, a redirect to the referer is returned.
     * Exclude sensitive fields from the old form data.
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
            $referer = $this->requestService->getReferer($request);
            $oldData = $request->getParsedBody();

            $sensitiveFields = ['password', 'confirmPassword'];

            $this->session->flash('errors', $e->errors);
            $this->session->flash('old', array_diff_key($oldData, array_flip($sensitiveFields)));

            return $response->withHeader('Location', $referer)->withStatus(302);
        }
    }
}
