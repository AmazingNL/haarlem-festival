<?php
// src/Domain/Event.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;
final class Event extends BaseEntity
{
    public ?int $event_id = null;
    public string $title = '';
    public string $slug = '';
    public string $description = '';
    public string $start_datetime = '';
    public string $end_datetime = '';

    public int $location_id = 0;
    public ?int $image_id = null;

    public int $is_published = 0;

    public ?string $created_at = null;
    public ?string $updated_at = null;
}
