<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Domain\Document;
use App\Validation\DocumentValidator;
use App\Validation\InMemoryRuleProvider;
use App\Validation\Rules\MaxDocumentSizeRule;
use App\Validation\Rules\RequiredMetadataRule;
use App\Validation\Rules\ProhibitedWordsRule;

$ruleProvider = new InMemoryRuleProvider();

$ruleProvider->setRulesForTenant('tenant_alpha', [
    new MaxDocumentSizeRule(120),
    new RequiredMetadataRule(['author', 'category']),
    new ProhibitedWordsRule(['confidential', 'restricted']),
]);

$validator = new DocumentValidator($ruleProvider);

echo "=== DEMO: MULTI-TENANT DOCUMENT VALIDATION ===\n\n";

// --- Valid Document ---
$validDocument = new Document(
    id: 'doc_001',
    tenantId: 'tenant_alpha',
    content: 'This is a clean and approved document content.',
    metadata: [
        'author' => 'Alice Smith',
        'category' => 'reports'
    ]
);

$resultSuccess = $validator->validate($validDocument);

printf("Document ID: %s | Tenant: %s\n", $validDocument->id, $validDocument->tenantId);
printf("Validation Status: %s\n\n", $resultSuccess->isValid ? 'SUCCESS (Valid)' : 'FAILED (Invalid)');

// --- Invalid Document ---
$invalidDocument = new Document(
    id: 'doc_002',
    tenantId: 'tenant_alpha',
    content: 'This document contains confidential data and is literally way too long to pass the size limitation rule defined for this specific tenant configuration.', //content exceeds 120 bytes and contains prohibited word 'confidential'
    metadata: [
        'category' => 'draft' // skipped 'author' intentionally
    ]
);

$resultFailure = $validator->validate($invalidDocument);

printf("Document ID: %s | Tenant: %s\n", $invalidDocument->id, $invalidDocument->tenantId);
printf("Validation Status: %s\n", $resultFailure->isValid ? 'SUCCESS (Valid)' : 'FAILED (Invalid)');

if (!$resultFailure->isValid) {
    echo "Validation Errors:\n";
    foreach ($resultFailure->errors as $index => $error) {
        printf("  %d. %s\n", $index + 1, $error);
    }
}
