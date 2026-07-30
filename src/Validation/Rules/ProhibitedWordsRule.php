<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Domain\Document;
use App\Validation\ValidationResult;
use App\Validation\ValidationRule;

final class ProhibitedWordsRule implements ValidationRule
{
    /**
     * @param string[] $prohibitedWords
     */
    public function __construct(
        private readonly array $prohibitedWords
    ) {}

    public function validate(Document $document): ValidationResult
    {
        $foundWords = [];

        foreach ($this->prohibitedWords as $word) {
            // stripos is used for case-insensitive search
            if (stripos($document->content, $word) !== false) {
                $foundWords[] = $word;
            }
        }

        if (!empty($foundWords)) {
            return ValidationResult::failure(
                sprintf("Document contains prohibited words: %s.", implode(', ', $foundWords))
            );
        }

        return ValidationResult::success();
    }
}
