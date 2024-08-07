<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Contracts\RequestValidatorFactoryInterface;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\RequestValidators\CreateCategoryRequestValidator;

class CategoriesController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory
    ) {
    }

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'categories/index.twig');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(CreateCategoryRequestValidator::class)
            ->validate($request->getParsedBody());

        $this->auth->register(new RegisterUserData(
            $data['name'],
            $data['email'],
            $data['password']
        ));

        return $response->withHeader('Location', '/categories')->withStatus(302);
    }
    public function delete(Request $request, Response $response): Response
    {
        //Placeholder
        return $response->withHeader('Location', '/categories')->withStatus(302);
    }
}
