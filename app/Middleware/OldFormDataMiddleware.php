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
 * Middleware that adds any old form data to the Twig environment global scope so it is available in the template.
 * Used to save user input fields, so there is no need to re-enter them.
 *
 * @package Middleware
 */
class OldFormDataMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Twig $twig,
        private readonly SessionInterface $session
    ) {
    }
    /**
     * Process the request and handle any old form data that occurs.
     * If old form data exists in the session, it will be added to the Twig environment global scope under the key 'old'.
     *
     * @param  ServerRequestInterface  $request     The request object.
     * @param  RequestHandlerInterface $handler     The request handler.
     * @return ResponseInterface                     The response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($old = $this->session->getFlash('old')) {
            $this->twig->getEnvironment()->addGlobal('old', $old);
        }

        return $handler->handle($request);
    }
}
