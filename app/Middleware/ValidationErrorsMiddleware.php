<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

/**
 * Middleware that adds any validation errors to the Twig environment global scope so they are available in the template.
 *
 * @package Middleware
 */
class ValidationErrorsMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly Twig $twig)
    {
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
        if (!empty($_SESSION['errors'])) {
            $this->twig->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
        }

        return $handler->handle($request);
    }
}
