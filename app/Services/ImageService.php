<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Image;
use App\Repositories\IImageRepository;
use DateTime;


final class ImageService implements IImageService
{

    private IImageRepository $imageRepository;

    public function __construct(IImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function saveImage(Image $image): int{
        $image->created_at = (new DateTime())->format('Y-m-d H:i:s');
        $image_id = $this->imageRepository->saveImage($image);
        return $image_id;
    }

    public function getImageById($image_id): Image{
        return $this->imageRepository->getImageById($image_id);
    }

    public function getAllImage(): array{
        return $this->imageRepository->getAllImage();
    }
}