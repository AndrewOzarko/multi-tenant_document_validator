<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Domain\Document;
use App\Validation\ValidationResult;
use App\Validation\ValidationRule;

final class RequiredMetadataRule implements ValidationRule
{
    /**
     * @param string[] $requiredFields
     */
    public function __construct(
        private readonly array $requiredFields
    ) {}

    public function validate(Document $document): ValidationResult
    {
        $missingFields = [];

        foreach ($this->requiredFields as $field) {
            if (!array_key_exists($field, $document->metadata)) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return ValidationResult::failure(
                sprintf("Missing required metadata fields: %s.", implode(', ', $missingFields))
            );
        }

        return ValidationResult::success();
    }
}
