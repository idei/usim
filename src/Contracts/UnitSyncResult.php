<?php

namespace Idei\Usim\Contracts;

use ArrayAccess;
use Idei\Usim\Contracts\Concerns\ArrayAccessibleDto;

/**
 * Result DTO for organizational unit synchronization operations.
 *
 * @phpstan-consistent-constructor
 * @implements ArrayAccess<string, mixed>
 */
readonly class UnitSyncResult implements ArrayAccess, SyncResultInterface
{
    use ArrayAccessibleDto;

    /**
     * @param  array<int, string>  $generatedTranslationFiles
     * @param  array<int, string>  $errors
     */
    final public function __construct(
        public bool $skipped = false,
        public string $skipReason = '',
        public int $deletedCount = 0,
        public int $upsertedCount = 0,
        public int $hierarchyUpdatedCount = 0,
        public array $generatedTranslationFiles = [],
        public array $errors = []
    ) {}

    /**
     * Create a result representing a skipped operation.
     */
    public static function skipped(string $reason): static
    {
        return new static(
            skipped: true,
            skipReason: $reason
        );
    }

    public function isSkipped(): bool
    {
        return $this->skipped;
    }

    public function getSkipReason(): ?string
    {
        return $this->skipReason !== '' ? $this->skipReason : null;
    }

    public function isSuccess(): bool
    {
        return ! $this->skipped && empty($this->errors);
    }

    /**
     * @return list<string>
     */
    public function getErrors(): array
    {
        return array_values($this->errors);
    }

    /**
     * @return array{skipped: bool, skip_reason: string, deleted_count: int, upserted_count: int, hierarchy_updated_count: int, generated_translation_files: list<string>, errors: list<string>}
     */
    public function toArray(): array
    {
        return [
            'skipped' => $this->skipped,
            'skip_reason' => $this->skipReason,
            'deleted_count' => $this->deletedCount,
            'upserted_count' => $this->upsertedCount,
            'hierarchy_updated_count' => $this->hierarchyUpdatedCount,
            'generated_translation_files' => array_values($this->generatedTranslationFiles),
            'errors' => array_values($this->errors),
        ];
    }
}
