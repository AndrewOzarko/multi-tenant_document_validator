<?php

declare(strict_types=1);

namespace Tests\Validation;

use App\Domain\Document;
use App\Validation\DocumentValidator;
use App\Validation\RuleProvider;
use App\Validation\ValidationRule;
use App\Validation\ValidationResult;
use PHPUnit\Framework\TestCase;

final class DocumentValidatorTest extends TestCase
{
    public function test_it_returns_success_when_all_rules_pass(): void
    {
        $document = new Document('1', 'tenant_1', 'Any content');

        $mockRule1 = $this->createMock(ValidationRule::class);
        $mockRule1->expects($this->once())
            ->method('validate')
            ->with($document)
            ->willReturn(ValidationResult::success());

        $mockRule2 = $this->createMock(ValidationRule::class);
        $mockRule2->expects($this->once())
            ->method('validate')
            ->with($document)
            ->willReturn(ValidationResult::success());

        $mockProvider = $this->createMock(RuleProvider::class);
        $mockProvider->expects($this->once())
            ->method('getRulesForTenant')
            ->with('tenant_1')
            ->willReturn([$mockRule1, $mockRule2]);

        $validator = new DocumentValidator($mockProvider);

        $result = $validator->validate($document);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->errors);
    }

    public function test_it_accumulates_multiple_errors_from_different_rules(): void
    {
        $document = new Document('2', 'tenant_1', 'Any content');

        $mockRule1 = $this->createMock(ValidationRule::class);
        $mockRule1->method('validate')
            ->willReturn(ValidationResult::failure('Error from rule 1'));

        $mockRule2 = $this->createMock(ValidationRule::class);
        $mockRule2->method('validate')
            ->willReturn(ValidationResult::failure(['Error 1 from rule 2', 'Error 2 from rule 2']));

        $mockProvider = $this->createMock(RuleProvider::class);
        $mockProvider->method('getRulesForTenant')
            ->with('tenant_1')
            ->willReturn([$mockRule1, $mockRule2]);

        $validator = new DocumentValidator($mockProvider);

        $result = $validator->validate($document);

        $this->assertFalse($result->isValid);
        $this->assertCount(3, $result->errors);
        $this->assertSame([
            'Error from rule 1',
            'Error 1 from rule 2',
            'Error 2 from rule 2'
        ], $result->errors);
    }

    public function test_it_passes_when_tenant_has_no_rules(): void
    {
        $document = new Document('3', 'tenant_2', 'Any content');

        $mockProvider = $this->createMock(RuleProvider::class);
        $mockProvider->expects($this->once())
            ->method('getRulesForTenant')
            ->with('tenant_2')
            ->willReturn([]);

        $validator = new DocumentValidator($mockProvider);

        $result = $validator->validate($document);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->errors);
    }
}
