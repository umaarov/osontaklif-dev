<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    protected static $table = 'users';

    public static function create(string $firstName, string $email, string $password): ?User
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO " . static::$table . " (first_name, email, password, created_at, updated_at) VALUES (:first_name, :email, :password, NOW(), NOW())";
        $stmt = self::db()->prepare($sql);
        $stmt->execute([
            ':first_name' => $firstName,
            ':email' => $email,
            ':password' => $hashedPassword,
        ]);

        $id = self::db()->lastInsertId();
        return self::find($id);
    }

    public static function findByEmail(string $email): ?User
    {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new static($data) : null;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}