<?php

namespace App\Models;

use Core\Model;
use PDO;

class UserProfile extends Model
{
    protected static $table = 'user_profiles';

    public static function findBy($column, $value)
    {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE `{$column}` = ? LIMIT 1");
        $stmt->execute([$value]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new static($data) : null;
    }
}