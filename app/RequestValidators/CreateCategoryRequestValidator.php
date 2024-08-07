<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use Valitron\Validator;
use App\Exception\ValidationException;


/**
 * This class implements the RequestValidatorInterface and provides functionality for validating data
 * used to create a new category.
 *
 * @see \App\Contracts\RequestValidatorInterface
 */
class CreateCategoryRequestValidator implements RequestValidatorInterface
{
    /**
     * Validates the given data array to create a new category.
     *
     * The data array should contain the following key:
     * - name: The category's name.
     *
     * @param array $data The data to be validated.
     * @return array The validated data.
     * @throws ValidationException If the validation fails.
     */
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', 'name');
        $v->rule('lengthMax', 'name', 50);

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
