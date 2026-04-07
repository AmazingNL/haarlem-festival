<?php
declare(strict_types=1);
namespace App\Services;

use App\Models\Image;
use App\Repositories\IImageRepository;
use DateTime;
use Exception;
use Throwable;
use Dom\HTMLDocument;




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

    public function storeUploadedImage(array $file, array $options = []): string
    {
        $validatedFile = $this->validateUpload($file);
        return $this->saveToFilesystem($validatedFile, $options);
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

    private function saveToFilesystem(array $file, array $options = []): string
    {
        $ext = $file['_ext'] ?? 'png';
        $folder = $this->sanitizeFolder((string) ($options['folder'] ?? 'admin'));
        $prefix = $this->slugify((string) ($options['prefix'] ?? ''));

        $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/' . $folder . '/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            throw new Exception('Upload directory not writable', 500);
        }

        if (!is_writable($uploadDir)) {
            throw new Exception('Upload directory not writable', 500);
        }

        $name = $this->buildFileName($uploadDir, $prefix, $ext);
        $dest = $uploadDir . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new Exception('Could not save file', 500);
        }

        @chmod($dest, 0644);

        return '/assets/images/' . $folder . '/' . $name;
    }

    private function buildFileName(string $uploadDir, string $prefix, string $ext): string
    {
        if ($prefix === '') {
            try {
                return bin2hex(random_bytes(16)) . '.' . $ext;
            } catch (Throwable $e) {
                throw new Exception('Failed to generate filename', 500);
            }
        }

        $candidate = $prefix . '.' . $ext;
        $counter = 1;

        while (file_exists($uploadDir . $candidate)) {
            $candidate = $prefix . '-' . $counter . '.' . $ext;
            $counter++;
        }

        return $candidate;
    }

    private function sanitizeFolder(string $folder): string
    {
        $folder = strtolower(trim($folder));
        $folder = preg_replace('/[^a-z0-9_-]/', '', $folder) ?? '';

        return $folder !== '' ? $folder : 'admin';
    }

    private function slugify(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        $value = trim($value, '-');

        return $value;
    }

    public function extractUrls(string $html): array
    {
        if ($html === '') {
            return [];
        }

        $dom = HTMLDocument::createFromString($html, LIBXML_NOERROR);
        $error = libxml_get_errors();
        if (!empty($error)) {
            throw new \ErrorException('No HTML file found');
        }

        $images = $dom->getElementsByTagName('img');
        $urls = [];

        if (empty($images)) {
            throw new \ErrorException('No img tag');
        }

        foreach ($images as $img) {
            $src = trim($img->getAttribute('src'));
            if ($src === '' || str_starts_with($src, 'data:')) {
                throw new \ErrorException('src can not be empty');
            }
            $urls[] = $src;
        }

        return array_values(array_unique($urls));
    }
}
