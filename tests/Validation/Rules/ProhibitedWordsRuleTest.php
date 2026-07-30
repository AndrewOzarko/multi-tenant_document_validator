<?php

declare(strict_types=1);

namespace Tests\Validation\Rules;

use App\Domain\Document;
use App\Validation\Rules\ProhibitedWordsRule;
use PHPUnit\Framework\TestCase;

final class ProhibitedWordsRuleTest extends TestCase
{
    public function test_it_passes_when_no_prohibited_words_found(): void
    {
        $rule = new ProhibitedWordsRule(['spam', 'fake']);
        $document = new Document('1', 't1', 'This is a clean text.');

        $result = $rule->validate($document);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->errors);
    }

    public function test_it_fails_when_prohibited_words_are_present(): void
    {
        $rule = new ProhibitedWordsRule(['spam', 'fake']);
        $document = new Document('2', 't1', 'This is a SpAm message with FaKe info.');

        $result = $rule->validate($document);

        $this->assertFalse($result->isValid);
        $this->assertCount(1, $result->errors);
        $this->assertStringContainsString('spam, fake', $result->errors[0]);
    }
}
