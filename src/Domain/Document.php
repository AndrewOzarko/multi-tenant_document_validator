<?php

declare(strict_types=1);

namespace App\Domain;

final class Document
{
    /**
     * @param string $id
     * @param string $tenantId
     * @param string $content
     * @param array<string, mixed> $metadata
     */
    public function __construct(
        public readonly string $id,
        public readonly string $tenantId,
        public readonly string $content,
        public readonly array $metadata = []
    ) {}
}
