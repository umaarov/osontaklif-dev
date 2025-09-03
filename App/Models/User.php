<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected static $table = 'users';

    public static function create(string $firstName, string $username, string $email, string $password): ?User
    {
        $user_data = [
            'public_id' => bin2hex(random_bytes(8)),
            'first_name' => $firstName,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
        ];

        $sql = "INSERT INTO users (public_id, first_name, username, email, password, created_at, updated_at) VALUES (:public_id, :first_name, :username, :email, :password, NOW(), NOW())";
        $stmt = self::db()->prepare($sql);
        $stmt->execute($user_data);

        $id = self::db()->lastInsertId();
        return self::find($id);
    }

    public static function findBy($column, $value)
    {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE `{$column}` = ? LIMIT 1");
        $stmt->execute([$value]);
        $data = $stmt->fetch();
        return $data ? new static($data) : null;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function update(array $data)
    {
        $sql = "UPDATE " . static::$table . " SET first_name = :first_name, updated_at = NOW() WHERE id = :id";
        $stmt = self::db()->prepare($sql);
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':id' => $this->id
        ]);
    }
}