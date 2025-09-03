<?php

namespace App\Models;

use Core\Model;

class Experience extends Model
{
    protected static $table = 'experiences';

    public static function findByUserId(int $userId)
    {
        return self::query("SELECT * FROM " . static::$table . " WHERE user_id = ? ORDER BY start_date DESC", [$userId]);
    }

    public static function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        $stmt = self::db()->prepare($sql);
        return $stmt->execute($data);
    }
}