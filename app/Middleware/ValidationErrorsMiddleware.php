<?php

declare(strict_types=1);

namespace App\Middleware;

use Slim\Views\Twig;
use App\Contracts\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that adds any validation errors to the Twig environment global scope so they are available in the template.
 *
 * @package Middleware
 */
class ValidationErrorsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Twig $twig,
        private readonly SessionInterface $session
    ) {
    }
    /**
     * Process the request and handle any validation errors that occur.
     * If errors exist in the session, they will be added to the Twig environment global scope.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($errors = $this->session->getFlash('errors')) {
            $this->twig->getEnvironment()->addGlobal('errors', $errors);
        }

        return $handler->handle($request);
    }
}
