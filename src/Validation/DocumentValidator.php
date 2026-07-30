<?php

declare(strict_types=1);

namespace App\Validation;

use App\Domain\Document;

final class DocumentValidator
{
    public function __construct(
        private readonly RuleProvider $ruleProvider
    ) {}

    public function validate(Document $document): ValidationResult
    {
        $rules = $this->ruleProvider->getRulesForTenant($document->tenantId);

        $allErrors = [];

        foreach ($rules as $rule) {
            $result = $rule->validate($document);

            if (!$result->isValid) {
                $allErrors = array_merge($allErrors, $result->errors);
            }
        }

        if (!empty($allErrors)) {
            return ValidationResult::failure($allErrors);
        }

        return ValidationResult::success();
    }
}
