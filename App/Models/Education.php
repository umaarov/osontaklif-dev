<?php
namespace App\Models;

use Core\Model;
use PDO;

class Education extends Model
{
    protected static $table = 'educations';

    public static function findByUserId(int $userId)
    {
        return self::query("SELECT * FROM " . static::$table . " WHERE user_id = ? ORDER BY start_date DESC", [$userId]);
    }
}