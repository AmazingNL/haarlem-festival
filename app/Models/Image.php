<?php
// src/Domain/Image.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class Image extends BaseEntity
{
    public ?int $image_id = null;
    public string $file_path = '';
    public ?string $alt_text = null;
    public int $uploaded_by_user_id = 0;
    public ?string $created_at = null;
}
