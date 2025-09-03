<?php

namespace App\Http\Controllers;

use App\Models\User;
use Core\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $this->view('auth.login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember_me']);

        $user = User::findByEmail($email);

        if (!$user || !$user->verifyPassword($password)) {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: login.php');
            exit();
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name;

        if ($rememberMe) {
            $this->createRememberMeToken($user->id);
        }

        $_SESSION['success'] = 'Successfully logged in!';
        header('Location: home.php');
        exit();
    }


    public function showRegisterForm()
    {
        $this->view('auth.register');
    }

    public function register()
    {
        $firstName = $_POST['first_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';

        if (empty($firstName) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Please fill in all fields.';
            header('Location: /register.php');
            exit();
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Passwords do not match.';
            header('Location: /register.php');
            exit();
        }

        if (User::findByEmail($email)) {
            $_SESSION['error'] = 'An account with this email already exists.';
            header('Location: /register.php');
            exit();
        }

        $user = User::create($firstName, $email, $password);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name;
        $_SESSION['success'] = 'Registration successful!';

        header('Location: /home.php');
        exit();
    }

    public function logout()
    {
        $this->clearRememberMeToken();

        session_unset();
        session_destroy();

        session_start();
        $_SESSION['success'] = 'You have been logged out.';
        header('Location: home.php');
        exit();
    }

    private function createRememberMeToken(int $userId): void
    {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        $hashedValidator = hash('sha256', $validator);
        $expiresAt = date('Y-m-d H:i:s', time() + (86400 * 30));

        $sql = "INSERT INTO auth_tokens (selector, hashed_validator, user_id, expires_at) VALUES (:selector, :hashed_validator, :user_id, :expires_at)";
        $stmt = User::db()->prepare($sql);
        $stmt->execute([
            ':selector' => $selector,
            ':hashed_validator' => $hashedValidator,
            ':user_id' => $userId,
            ':expires_at' => $expiresAt
        ]);

        $cookieValue = $selector . ':' . $validator;
        setcookie('remember_me', $cookieValue, time() + (86400 * 30), "/", "", false, true);
    }

    private function clearRememberMeToken(): void
    {
        if (isset($_COOKIE['remember_me'])) {
            list($selector, $validator) = explode(':', $_COOKIE['remember_me'], 2);

            $sql = "DELETE FROM auth_tokens WHERE selector = :selector";
            $stmt = User::db()->prepare($sql);
            $stmt->execute([':selector' => $selector]);

            setcookie('remember_me', '', time() - 3600, "/");
        }
    }
}