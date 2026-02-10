<?php
// src/Domain/ProgramItem.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
use App\Models\Enum\ProgramItemSource;

final class ProgramItem extends BaseEntity
{
    public ?int $program_item_id = null;
    public int $user_id = 0;
    public int $event_id = 0;

    public ProgramItemSource $source = ProgramItemSource::saved;

    public ?string $created_at = null;

    /** @param array<string,mixed> $row */
        public static function fromArray(array $row): static
    {
        $rawSource = $row['source'] ?? null;
        unset($row['source']); // prevent parent from assigning a raw string to the enum-typed property

        $pi = parent::fromArray($row);

        if ($rawSource !== null) {
            // Prefer tryFrom to avoid ValueError on invalid values (PHP 8.1+)
            $source = ProgramItemSource::tryFrom((string)$rawSource);
            if ($source !== null) $pi->source = $source;
        }

        return $pi;
    }
}
