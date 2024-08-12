<?php

declare(strict_types=1);

namespace App\Controllers;

use Slim\Views\Twig;
use App\ResponseFormatter;
use App\Services\CategoryService;
use App\Contracts\RequestValidatorFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\RequestValidators\CreateCategoryRequestValidator;

/**
 * This class is responsible for handling HTTP requests related to categories.
 * It provides methods for rendering the index page, storing a new category, and deleting a category.
 */
class CategoriesController
{
    /**
     * @param Twig $twig The Twig template engine.
     * @param RequestValidatorFactoryInterface $requestValidatorFactory The request validator factory.
     * @param CategoryService $categoryService The category service.
     */
    public function __construct(
        private readonly Twig $twig,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly CategoryService $categoryService,
        private readonly ResponseFormatter $responseFormatter
    ) {}

    /**
     * Render the index page of categories.
     *
     * @param Request $request The HTTP request.
     * @param Response $response The HTTP response.
     * @return Response The rendered response.
     */
    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'categories/index.twig', ['categories' => $this->categoryService->getAll()]);
    }

    /**
     * Store a new category.
     *
     * @param Request $request The HTTP request.
     * @param Response $response The HTTP response.
     * @return Response The response with a redirect to the categories page.
     */
    public function store(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(CreateCategoryRequestValidator::class)
            ->validate($request->getParsedBody());

        $this->categoryService->create($data['name'], $request->getAttribute('user'));

        return $response->withHeader('Location', '/categories')->withStatus(302);
    }

    /**
     * Delete a category.
     *
     * @param Request $request The HTTP request.
     * @param Response $response The HTTP response.
     * @param array $args The route arguments.
     * @return Response The response with a redirect to the categories page.
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $this->categoryService->delete((int) $args['id']);

        return $response->withHeader('Location', '/categories')->withStatus(302);
    }
    /**
     * Retrieve a category by its ID and return it as JSON.
     *
     * @param Request $request The HTTP request.
     * @param Response $response The HTTP response.
     * @param array $args The route arguments.
     * @return Response The response containing the category data as JSON.
     *         If the category is not found, a 404 status code is returned.
     */
    public function get(Request $request, Response $response, array $args): Response
    {
        // Retrieve the category by its ID
        $category = $this->categoryService->getById((int) $args['id']);

        // If the category does not exist, return a 404 status code
        if (!$category) {
            return $response->withStatus(404);
        }

        // Prepare the category data to be returned as JSON
        $data = [
            'id' => $category->getId(),
            'name' => $category->getName()
        ];

        return $this->responseFormatter->asJson($response, $data);
    }
}
