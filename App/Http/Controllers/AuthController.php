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

        $user = User::findByEmail($email);

        if (!$user || !$user->verifyPassword($password)) {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: /login.php');
            exit();
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name;
        $_SESSION['success'] = 'Successfully logged in!';

        header('Location: /home.php');
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
        session_unset();
        session_destroy();
        header('Location: /home.php');
        exit();
    }
}