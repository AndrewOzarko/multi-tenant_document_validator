<?php

declare(strict_types=1);

namespace Tests\Validation;

use App\Domain\Document;
use App\Validation\DocumentValidator;
use App\Validation\InMemoryRuleProvider;
use App\Validation\Rules\MaxDocumentSizeRule;
use App\Validation\Rules\RequiredMetadataRule;
use PHPUnit\Framework\TestCase;

final class DocumentValidatorTest extends TestCase
{
    private DocumentValidator $validator;

    protected function setUp(): void
    {
        $provider = new InMemoryRuleProvider();

        $provider->setRulesForTenant('tenant_1', [
            new MaxDocumentSizeRule(50),
            new RequiredMetadataRule(['status'])
        ]);

        $this->validator = new DocumentValidator($provider);
    }

    public function test_it_returns_success_for_valid_document(): void
    {
        $document = new Document('1', 'tenant_1', 'Short text', ['status' => 'draft']);

        $result = $this->validator->validate($document);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->errors);
    }

    public function test_it_accumulates_multiple_errors(): void
    {
        $document = new Document('2', 'tenant_1', str_repeat('A', 60), ['other_field' => 'value']);

        $result = $this->validator->validate($document);

        $this->assertFalse($result->isValid);
        $this->assertCount(2, $result->errors);
    }

    public function test_it_passes_when_tenant_has_no_rules(): void
    {
        $document = new Document('3', 'tenant_2', 'Any content');

        $result = $this->validator->validate($document);

        $this->assertTrue($result->isValid);
    }
}
