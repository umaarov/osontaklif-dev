<?php

namespace Core;

class Cache
{
    private string $cacheDir = BASE_PATH . '/storage/cache';

    public function __construct()
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
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
        $data = [
            'expires' => time() + $ttl,
            'value' => $value,
        ];
        file_put_contents($this->getFilePath($key), serialize($data));
    }

    private function getFilePath(string $key): string
    {
        return $this->cacheDir . '/' . sha1($key) . '.cache';
    }
}