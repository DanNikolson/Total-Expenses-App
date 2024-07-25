<?php

declare(strict_types=1);

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Entity\User;
use Valitron\Validator;
use Doctrine\ORM\EntityManager;
use App\Exception\ValidationException;

/**
 * This class implements the RequestValidatorInterface and provides functionality for validating data
 * used to register a new user.
 * @see \App\Contracts\RequestValidatorInterface
 */
class RegisterUserRequestValidator implements RequestValidatorInterface
{
    /**
     * @param EntityManager $entityManager The Doctrine EntityManager used to check if the user already exists in the database.
     */
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /**
     * Validates the given data array to register a new user.
     *
     * The data array should contain the following keys:
     * - name: The user's name.
     * - email: The user's email address.
     * - password: The user's password.
     * - confirmPassword: The user's password confirmed by the user.
     *
     * @param array $data The data to be validated.
     * @return array The validated data.
     * @throws ValidationException If the validation fails.
     */
    public function validate(array $data): array
    {
        $v = new Validator($data);
        $v->rule('required', ['name', 'email', 'password', 'confirmPassword']);
        $v->rule('email', 'email');
        $v->rule('equals', 'confirmPassword', 'password')->label('Confirm Password');
        $v->rule(
            fn ($field, $value, $params, $fields) => !$this->entityManager
                ->getRepository(User::class)
                ->count(['email' => $value]),
            "email"
        )->message("User with the given email address already exists");

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
