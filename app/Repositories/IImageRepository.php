<?php
declare(strict_types=1);

namespace App\Repositories;
use App\Models\Image;

interface IImageRepository {

    public function saveImage(Image $image): int;
    public function getImageById($image_id): Image;
    public function getAllImage(): array;
}