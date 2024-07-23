<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that checks if the user is authenticated by checking if the 'user' session variable is set.
 * If the user is authenticated, it adds the user entity to the request attribute 'user', thus making it accessible throuthout the request.
 * If the user is not authenticated, it does nothing.
 */
class AuthenticateMiddleware implements MiddlewareInterface
{
    /**
     * Create a new instance of the AuthenticateMiddleware.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager used to retrieve the user entity from the database.
     */
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /**
     * Process the request and handle the authentication.
     * If the user is not authenticated, it does nothing.
     * If the user is authenticated, it adds the user entity to the request attribute 'user'.
     *
     * @param ServerRequestInterface $request The request object.
     * @param RequestHandlerInterface $handler The request handler.
     * @return ResponseInterface The response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!empty($_SESSION['user'])) {
            $user = $this->entityManager->getRepository(User::class)->find($_SESSION['user']);

            $request = $request->withAttribute('user', $user);
        }

        return $handler->handle($request);
    }
}
