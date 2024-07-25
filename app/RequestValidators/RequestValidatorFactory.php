<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Contracts\RequestValidatorFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * Factory for creating instances of RequestValidatorInterface.
 *
 * @see \App\Contracts\RequestValidatorInterface
 *
 * @package App\RequestValidators
 */
class RequestValidatorFactory implements RequestValidatorFactoryInterface
{
    /**
     * The container that will be used to instantiate the RequestValidatorInterface implementations.
     *
     * @var ContainerInterface
     */
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    /**
     * Create a new instance of the given RequestValidatorInterface implementation class.
     *
     * @param string $class The fully qualified class name of the RequestValidatorInterface implementation.
     *
     * @return RequestValidatorInterface
     *
     * @throws \RuntimeException If the provided class is not an implementation of RequestValidatorInterface.
     */
    public function make(string $class): RequestValidatorInterface
    {
        $validator = $this->container->get($class);

        if ($validator instanceof RequestValidatorInterface) {
            return $validator;
        }

        throw new \RuntimeException('Failed to instantiate the request validator class "' . $class . '"');
    }
}
