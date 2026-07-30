<?php

declare(strict_types=1);

namespace App\Validation;

interface RuleProvider
{
    /**
     *
     * @param string $tenantId
     * @return ValidationRule[]
     */
    public function getRulesForTenant(string $tenantId): array;
}
