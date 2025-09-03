<?php

namespace Core;

class UploaderService
{
    private string $uploadDir;
    private array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private int $maxSize = 5 * 1024 * 1024;

    public function __construct(string $subDirectory = 'avatars')
    {
        $this->uploadDir = BASE_PATH . '/public/uploads/' . $subDirectory;
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function handle(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            $_SESSION['error'] = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
            return null;
        }

        if ($file['size'] > $this->maxSize) {
            $_SESSION['error'] = 'File is too large. Maximum size is 5MB.';
            return null;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = bin2hex(random_bytes(16)) . '.' . $extension;
        $destination = $this->uploadDir . '/' . $newFilename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/uploads/avatars/' . $newFilename;
        }

        return null;
    }
}