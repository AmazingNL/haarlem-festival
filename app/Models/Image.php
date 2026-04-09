<?php
// src/Domain/Image.php

declare(strict_types=1);
namespace App\Models;
use App\Core\BaseEntity;

final class Image
{

    public ?int $image_id = null;
    public string $file_path = '';
    public ?string $alt_text = null;
    public int $uploaded_by_user_id = 0;
    public ?string $created_at = null;

    public function __construct($image_id, $file_path, $alt_text, $uploaded_by_user_id, $created_at)
    {
        $this->image_id = $image_id;
        $this->file_path = $file_path;
        $this->alt_text = $alt_text;
        $this->uploaded_by_user_id = $uploaded_by_user_id;
        $this->created_at = $created_at;

    }
}
