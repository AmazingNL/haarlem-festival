<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enum\ProgramItemSource;

final class ProgramItem
{
    public ?int $program_item_id = null;
    public int $user_id = 0;
    public int $event_id = 0;
    public ProgramItemSource $source = ProgramItemSource::saved;
    public ?string $created_at = null;

    public function __construct(
        ?int $program_item_id = null,
        int $user_id = 0,
        int $event_id = 0,
        ProgramItemSource $source = ProgramItemSource::saved,
        ?string $created_at = null
    ) {
        $this->program_item_id = $program_item_id;
        $this->user_id = $user_id;
        $this->event_id = $event_id;
        $this->source = $source;
        $this->created_at = $created_at;
    }
}
