<?php

declare(strict_types=1);

namespace App\Validation;

use InvalidArgumentException; // Додаємо виняток

final class ValidationResult
{
    /**
     * @param string[] $errors
     */
    private function __construct(
        public readonly bool $isValid,
        public readonly array $errors
    ) {}

    public static function success(): self
    {
        return new self(isValid: true, errors: []);
    }

    /**
     * @param string|string[] $errors
     */
    public static function failure(string|array $errors): self
    {
        $errorsArray = is_array($errors) ? $errors : [$errors];

        if (empty($errorsArray)) {
            throw new InvalidArgumentException('Failure result must contain at least one error.');
        }

        return new self(isValid: false, errors: $errorsArray);
    }
}
