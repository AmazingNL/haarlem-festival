<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Image;
use App\Repositories\IImageRepository;
use DateTime;
use Exception;
use Throwable;




final class ImageService implements IImageService
{

    private IImageRepository $imageRepository;

    public function __construct(IImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function saveImage(Image $image): int
    {
        $image->created_at = (new DateTime())->format('Y-m-d H:i:s');
        $image_id = $this->imageRepository->saveImage($image);
        return $image_id;
    }

    public function getImageById($image_id): Image
    {
        return $this->imageRepository->getImageById($image_id);
    }

    public function getAllImage(): array
    {
        return $this->imageRepository->getAllImage();
    }

    public function storeUploadedImage(array $file): string
    {
        $validatedFile = $this->validateUpload($file);
        return $this->saveToFilesystem($validatedFile);
    }
    public function validateUpload(array $file): array
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload failed', 400);
        }

        if (!isset($file['size']) || $file['size'] > 5 * 1024 * 1024) {
            throw new Exception('File too large', 413);
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if (!isset($allowed[$mime])) {
            throw new Exception('Invalid image type', 415);
        }

        $file['_ext'] = $allowed[$mime];
        return $file;
    }

    private function saveToFilesystem(array $file): string
    {
        $ext = $file['_ext'] ?? 'png';

        try {
            $name = bin2hex(random_bytes(16)) . '.' . $ext;
        } catch (Throwable $e) {
            throw new Exception('Failed to generate filename', 500);
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/admin/';
        if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
            throw new Exception('Upload directory not writable', 500);
        }

        $dest = $uploadDir . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new Exception('Could not save file', 500);
        }

        @chmod($dest, 0644);

        return '/assets/images/admin/' . $name;
    }

    public function extractUrls(string $html): array
    {
        if ($html === '') {
            return [];
        }

        $urls = [];
        $previous = libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML('<!DOCTYPE html><html><body>' . $html . '</body></html>', LIBXML_NOERROR | LIBXML_NOWARNING);
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            if (!($img instanceof \DOMElement)) {
                continue;
            }
            $src = trim($img->getAttribute('src'));
            if ($src === '' || str_starts_with($src, 'data:')) {
                continue;
            }
            $urls[] = $src;
        }

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return array_values(array_unique($urls));
    }
}