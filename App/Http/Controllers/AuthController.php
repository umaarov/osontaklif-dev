<?php

namespace App\Http\Controllers;

use App\Models\User;
use Core\AuthService;
use Core\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $this->view('auth.login');
    }

    public function showRegisterForm()
    {
        $this->view('auth.register');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember_me']);

        if (AuthService::attempt($email, $password, $remember)) {
            $_SESSION['success'] = 'Successfully logged in!';
            header('Location: home.php');
            exit();
        }

        $_SESSION['error'] = 'Invalid email or password.';
        header('Location: login.php');
        exit();
    }

    public function register()
    {
        $firstName = $_POST['first_name'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';

        if (empty($firstName) || empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Please fill in all fields.';
            header('Location: register.php');
            exit();
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Passwords do not match.';
            header('Location: register.php');
            exit();
        }

        if (User::findBy('username', $username)) {
            $_SESSION['error'] = 'Username is already taken.';
            header('Location: register.php');
            exit();
        }
        if (User::findBy('email', $email)) {
            $_SESSION['error'] = 'Email is already in use.';
            header('Location: register.php');
            exit();
        }

        $user = User::create($firstName, $username, $email, $password);
        if ($user) {
            AuthService::attempt($email, $password);
            $_SESSION['success'] = 'Registration successful!';
            header('Location: home.php');
            exit();
        }

        $_SESSION['error'] = 'Could not create account.';
        header('Location: register.php');
        exit();
    }

    public function logout()
    {
        AuthService::logout();
        $_SESSION['success'] = 'You have been logged out.';
        header('Location: home.php');
        exit();
    }
}