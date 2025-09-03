<?php

namespace Core;

use App\Models\User;

class AuthService
{
    private static ?User $user = null;

    public static function attempt(string $email, string $password, bool $remember = false): bool
    {
        $user = User::findBy('email', $email);

        if (!$user || !$user->verifyPassword($password)) {
            return false;
        }

        self::establishSession($user);

        if ($remember) {
            self::createRememberMeToken($user->id);
        }
        return true;
    }

    public static function loginFromCookie(): void
    {
        if (self::check() || !isset($_COOKIE['remember_me'])) {
            return;
        }

        list($selector, $validator) = explode(':', $_COOKIE['remember_me'], 2);

        if (!$selector || !$validator) return;

        $sql = "SELECT * FROM auth_tokens WHERE selector = :selector AND expires_at >= NOW() LIMIT 1";
        $stmt = Database::getInstance()->getConnection()->prepare($sql);
        $stmt->execute([':selector' => $selector]);
        $token = $stmt->fetch();

        if ($token && hash_equals($token['hashed_validator'], hash('sha256', $validator))) {
            $user = User::find($token['user_id']);
            if ($user) {
                self::establishSession($user);
            }
        }
    }

    public static function logout(): void
    {
        if (isset($_COOKIE['remember_me'])) {
            list($selector) = explode(':', $_COOKIE['remember_me'], 2);
            $sql = "DELETE FROM auth_tokens WHERE selector = :selector";
            $stmt = User::db()->prepare($sql);
            $stmt->execute([':selector' => $selector]);
            setcookie('remember_me', '', time() - 3600, "/");
        }

        session_unset();
        session_destroy();
        session_start();
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?User
    {
        if (!self::check()) {
            return null;
        }
        if (self::$user === null) {
            self::$user = User::find($_SESSION['user_id']);
        }
        return self::$user;
    }

    private static function establishSession(User $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name;
    }

    private static function createRememberMeToken(int $userId): void
    {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));

        $tokenData = [
            'selector' => $selector,
            'hashed_validator' => hash('sha256', $validator),
            'user_id' => $userId,
            'expires_at' => date('Y-m-d H:i:s', time() + (86400 * 30))
        ];

        $sql = "INSERT INTO auth_tokens (selector, hashed_validator, user_id, expires_at) VALUES (:selector, :hashed_validator, :user_id, :expires_at)";
        $stmt = User::db()->prepare($sql);
        $stmt->execute($tokenData);

        setcookie('remember_me', $selector . ':' . $validator, time() + (86400 * 30), "/", "", false, true);
    }
}