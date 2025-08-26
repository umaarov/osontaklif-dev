<?php

namespace App\Models;

use Core\Model;

class Profession extends Model
{
    protected static $table = 'professions';

    public static function findOneBy($column, $value)
    {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        $data = $stmt->fetch();
        return $data ? new static($data) : null;
    }
}
