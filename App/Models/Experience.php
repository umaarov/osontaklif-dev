<?php
namespace App\Models;

use Core\Model;
use PDO;

class Experience extends Model
{
    protected static $table = 'experiences';

    public static function findByUserId(int $userId)
    {
        return self::query("SELECT * FROM " . static::$table . " WHERE user_id = ? ORDER BY start_date DESC", [$userId]);
    }
}