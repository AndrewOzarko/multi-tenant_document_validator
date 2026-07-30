<?php

declare(strict_types=1);

namespace App\Validation;

use App\Domain\Document;

interface  ValidationRule
{
    public function validate(Document $document): ValidationResult;
}
