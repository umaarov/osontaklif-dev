<?php

namespace App\Models;

use Core\Model;
use InvalidArgumentException;

class Profession extends Model
{
    protected static $table = 'professions';

    public static function findOneBy($column, $value)
    {
        $allowedColumns = ['id', 'slug', 'name', 'is_active'];

        if (!in_array($column, $allowedColumns)) {
            throw new InvalidArgumentException("Invalid column name provided: {$column}");
        }

        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE `{$column}` = ? LIMIT 1");
        $stmt->execute([$value]);
        $data = $stmt->fetch();
        return $data ? new static($data) : null;
    }
}
