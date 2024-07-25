<?php

declare(strict_types=1);

namespace App\RequestValidators;

use Valitron\Validator;
use App\Contracts\RequestValidatorInterface;
use App\Exception\ValidationException;

/**
 * Implementation of the RequestValidatorInterface for user login requests.
 * 
 * @see \App\Contracts\RequestValidatorInterface
 *
 * This class validates the user login request data, ensuring that the "email" and "password" fields are present and
 * that the "email" field is a valid email address.
 */
class UserLoginRequestValidator implements RequestValidatorInterface
{
    /**
     * Validates the given data array for a user login request.
     *
     * @param array $data The data to be validated.
     *
     * @return array The validated data.
     *
     * @throws ValidationException If the validation fails.
     */
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['email', 'password']);
        $v->rule('email', 'email');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
