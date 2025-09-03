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

    public static function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        $stmt = self::db()->prepare($sql);
        $stmt->execute($data);
    }

    public function update(array $data)
    {
        $setClauses = [];
        foreach ($data as $key => $value) {
            $setClauses[] = "`{$key}` = :{$key}";
        }
        $setClause = implode(', ', $setClauses);

        $sql = "UPDATE " . static::$table . " SET {$setClause} WHERE user_id = :user_id";
        $stmt = self::db()->prepare($sql);

        $data['user_id'] = $this->user_id;
        $stmt->execute($data);
    }
}