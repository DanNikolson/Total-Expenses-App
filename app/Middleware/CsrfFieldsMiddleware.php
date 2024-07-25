<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that adds CSRF fields to the Twig environment global scope.
 */
class CsrfFieldsMiddleware implements MiddlewareInterface
{
    /**
     * The Twig environment used to render templates.
     */
    public function __construct(
        private readonly Twig $twig,
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * Process the request and add the CSRF fields to the Twig environment global scope.
     *
     * @param ServerRequestInterface $request The request object.
     * @param RequestHandlerInterface $handler The request handler.
     * @return ResponseInterface The response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $csrf = $this->container->get('csrf');

        $csrfNameKey  = $csrf->getTokenNameKey();
        $csrfValueKey = $csrf->getTokenValueKey();
        $csrfName     = $csrf->getTokenName();
        $csrfValue    = $csrf->getTokenValue();
        $fields       = <<<CSRF_FIELDS
            <input type="hidden" name="$csrfNameKey" value="$csrfName">
            <input type="hidden" name="$csrfValueKey" value="$csrfValue">
CSRF_FIELDS;

        $this->twig->getEnvironment()->addGlobal('csrf', [
            'keys' => [
                'name'  => $csrfNameKey,
                'value' => $csrfValueKey
            ],
            'name'  => $csrfName,
            'value' => $csrfValue,
            'fields' => $fields
        ]);

        return $handler->handle($request);
    }
}
