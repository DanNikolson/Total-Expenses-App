<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use Valitron\Validator;
use App\Exception\ValidationException;

/**
 * This class  provides functionality for validating data used to update a category.
 *
 * The data array should contain the following key:
 * - name: The category's name.
 *
 * @see \App\Contracts\RequestValidatorInterface
 */
class UpdateCategoryRequestValidator implements RequestValidatorInterface
{
    /**
     * Validates the given data array to update a category.
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
