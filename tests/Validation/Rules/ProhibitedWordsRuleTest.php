<?php

declare(strict_types=1);

namespace Tests\Validation\Rules;

use App\Domain\Document;
use App\Validation\Rules\ProhibitedWordsRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProhibitedWordsRuleTest extends TestCase
{
    /**
     * @param string[] $prohibitedWords
     */
    #[DataProvider('provideValidationScenarios')]
    public function test_it_validates_document_content_correctly(
        array $prohibitedWords,
        string $content,
        bool $expectedIsValid
    ): void {
        $rule = new ProhibitedWordsRule($prohibitedWords);
        $document = new Document('1', 't1', $content);

        $result = $rule->validate($document);

        $this->assertSame($expectedIsValid, $result->isValid);

        if ($expectedIsValid) {
            $this->assertEmpty($result->errors);
        } else {
            $this->assertNotEmpty($result->errors);
            // Перевіряємо, що повідомлення про помилку містить згадку про заборонені слова
            $this->assertStringContainsString('spam', strtolower($result->errors[0]));
        }
    }

    public static function provideValidationScenarios(): array
    {
        return [
            'clean text' => [
                ['spam', 'fake'],
                'This is a clean text.',
                true
            ],
            'contains exact prohibited words' => [
                ['spam', 'fake'],
                'This is a spam message with fake info.',
                false
            ],
            'case insensitive check' => [
                ['spam', 'fake'],
                'This is a SpAm message with FaKe info.',
                false
            ],
            'empty prohibited words list allows anything' => [
                [],
                'This is a spam message with fake info.',
                true
            ],
        ];
    }
}
