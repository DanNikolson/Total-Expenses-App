<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Factory for creating instances of RequestValidatorInterface
 * @see \App\Contracts\RequestValidatorInterface
 */
interface RequestValidatorFactoryInterface
{
    /**
     * Create a new instance of the given RequestValidatorInterface implementation class.
     *
     * @param string $class The fully qualified class name of the RequestValidatorInterface implementation.
     * @return RequestValidatorInterface
     */
    public function make(string $class): RequestValidatorInterface;
}
