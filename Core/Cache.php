<?php

namespace Core;

use Closure;
use Memcached;

class Cache
{
    private static ?Memcached $memcached = null;

    public function __construct()
    {
    }

    private function connection(): ?Memcached
    {
        if (!class_exists('\Memcached')) {
            return null;
        }

        if (self::$memcached === null) {
            self::$memcached = new Memcached();
            if (!self::$memcached->addServer('127.0.0.1', 11211)) {
                return null;
            }
        }
        return self::$memcached;
    }

    public function get(string $key)
    {
        if (!$conn = $this->connection()) {
            return null;
        }

        $value = $conn->get($this->formatKey($key));

        return $value === false ? null : $value;
    }

    public function set(string $key, $value, int $ttl = 3600): void
    {
        if ($conn = $this->connection()) {
            $conn->set($this->formatKey($key), $value, $ttl);
        }
    }

    public function remember(string $key, int $ttl, Closure $callback)
    {
        $value = $this->get($key);

        if ($value !== null) {
            return $value;
        }

        $value = $callback();

        $this->set($key, $value, $ttl);

        return $value;
    }

    public function delete(string $key): bool
    {
        if ($conn = $this->connection()) {
            return $conn->delete($this->formatKey($key));
        }
        return false;
    }

    public function flush(): bool
    {
        if ($conn = $this->connection()) {
            return $conn->flush();
        }
        return false;
    }

    private function formatKey(string $key): string
    {
        return 'osontaklif:' . md5($key);
    }
}