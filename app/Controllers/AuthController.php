<?php

namespace App\Controllers;

use Slim\Views\Twig;
use App\Contracts\AuthInterface;
use App\DataObjects\RegisterUserData;
use App\Exception\ValidationException;
use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\UserLoginRequestValidator;
use App\RequestValidators\RegisterUserRequestValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly AuthInterface $auth,
    ) {
    }

    public function loginView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/login.twig');
    }

    public function registerView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/register.twig');
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)
            ->validate($request->getParsedBody());

        $this->auth->register(new RegisterUserData(
            $data['name'],
            $data['email'],
            $data['password']
        ));

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function logIn(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(UserLoginRequestValidator::class)
            ->validate($request->getParsedBody());

        if (!$this->auth->attemptLogin($data)) {
            throw new ValidationException(['password' => ['You have entered an invalid username or password']]);
        }
        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function logOut(Request $request, Response $response): Response
    {
        $this->auth->logOut();
        return $response->withHeader('Location', '/')->withStatus(302);
    }
}
