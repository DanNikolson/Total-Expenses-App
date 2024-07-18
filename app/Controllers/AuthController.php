<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class AuthController
{
    public function __construct(private readonly Twig $twig)
    {
    }

    public function loginView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'login.twig');
    }

    public function registerView(Request $request, Response $response): Response
    {
        //TODO: Registration view
    }

    public function Register(Request $request, Response $response): Response
    {
        // TODO: User Registration
        return $response;
    }
}
