<?php

declare(strict_types=1);

namespace App\Validation;

final class InMemoryRuleProvider implements RuleProvider
{
    /**
     * @var array<string, ValidationRule[]>
     */
    private array $tenantRules = [];

    /**
     *
     * @param string $tenantId
     * @param ValidationRule[] $rules
     */
    public function setRulesForTenant(string $tenantId, array $rules): void
    {
        $this->tenantRules[$tenantId] = $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getRulesForTenant(string $tenantId): array
    {
        return $this->tenantRules[$tenantId] ?? [];
    }
}
