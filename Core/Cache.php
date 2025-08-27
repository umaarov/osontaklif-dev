<?php

namespace Core;

use RuntimeException;

class Cache
{
    private string $cacheDir = BASE_PATH . '/storage/cache';

    public function __construct()
    {
        if (!is_dir($this->cacheDir)) {
            if (!mkdir($this->cacheDir, 0755, true) && !is_dir($this->cacheDir)) {
                throw new RuntimeException("Failed to create cache directory: {$this->cacheDir}");
            }
        }
    }


    public function get(string $key)
    {
        $file = $this->getFilePath($key);
        if (!file_exists($file)) {
            return null;
        }

        $data = unserialize(file_get_contents($file));
        if (time() > $data['expires']) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public function set(string $key, $value, int $ttl = 3600): void
    {
        $filePath = $this->getFilePath($key);
        $data = [
            'expires' => time() + $ttl,
            'value' => $value,
        ];

        if (file_put_contents($filePath, serialize($data)) === false) {
            throw new RuntimeException("Failed to write cache file: {$filePath}");
        }
    }


    private function getFilePath(string $key): string
    {
        return $this->cacheDir . '/' . sha1($key) . '.cache';
    }
}