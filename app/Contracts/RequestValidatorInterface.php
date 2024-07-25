<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Interface for classes that validate request data.
 *
 * This interface defines a single method that should be implemented by any class that
 * wants to validate request data. The validate method takes an array of data and returns
 * an array of validated data.
 *
 */
interface RequestValidatorInterface
{
    /**
     * Validates the given data array.
     *
     * @param array $data The data to be validated.
     * @return array The validated data.
     */
    public function validate(array $data): array;
}
