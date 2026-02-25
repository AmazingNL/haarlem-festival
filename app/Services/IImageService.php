<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Image;

interface IImageService {

public function saveImage(Image $image): int;
public function getImageById($image_id): Image;
public function getAllImage(): array;
}