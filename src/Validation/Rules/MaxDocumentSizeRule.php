<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Domain\Document;
use App\Validation\ValidationResult;
use App\Validation\ValidationRule;

final class MaxDocumentSizeRule implements ValidationRule
{
    public function __construct(
        private readonly int $maxBytes
    ) {}

    public function validate(Document $document): ValidationResult
    {
        $size = strlen($document->content);

        if ($size > $this->maxBytes) {
            return ValidationResult::failure(
                sprintf("Document size (%d bytes) exceeds the allowed maximum of %d bytes.", $size, $this->maxBytes)
            );
        }

        return ValidationResult::success();
    }
}
