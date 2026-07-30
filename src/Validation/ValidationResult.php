<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult
{
    /**
     * @param bool $isValid
     * @param string[] $errors
     */
    public function __construct(
        public readonly bool $isValid,
        public readonly array $errors = []
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

        return new self(isValid: false, errors: $errorsArray);
    }
}
