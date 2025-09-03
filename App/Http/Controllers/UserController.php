<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Core\AuthService;
use Core\Controller;
use Core\UploaderService;

class UserController extends Controller
{
    public function show($username)
    {
        $user = User::findBy('username', $username);
        if (!$user) {
            http_response_code(404);
            $this->view('errors.404');
            return;
        }

        $profile = UserProfile::findBy('user_id', $user->id);
        $this->view('users.profile', compact('user', 'profile'));
    }

    public function edit()
    {
        $user = AuthService::user();
        if (!$user) {
            header("Location: login.php");
            exit();
        }

        $profile = UserProfile::findBy('user_id', $user->id);
        $this->view('users.edit', compact('user', 'profile'));
    }

    public function update()
    {
        $user = AuthService::user();
        if (!$user) {
            exit('Unauthorized');
        }

        $avatarPath = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploader = new UploaderService();
            $avatarPath = $uploader->handle($_FILES['avatar']);
        }

        $user->update(['first_name' => $_POST['first_name'] ?? $user->first_name]);

        $profileData = [
            'surname' => $_POST['surname'] ?? null,
            'headline' => $_POST['headline'] ?? null,
            'bio' => $_POST['bio'] ?? null
        ];

        if ($avatarPath) {
            $profileData['avatar_url'] = $avatarPath;
        }

        $profile = UserProfile::findBy('user_id', $user->id);
        if ($profile) {
            $profile->update($profileData);
        } else {
            UserProfile::create(array_merge(['user_id' => $user->id], $profileData));
        }

        $_SESSION['success'] = 'Profile updated successfully!';
        header("Location: profile.php?user=" . $user->username);
        exit();
    }
}