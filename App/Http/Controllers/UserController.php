<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Education;
use App\Models\Experience;
use App\Models\User;
use App\Models\UserProfile;
use Core\Controller;

class UserController extends Controller
{
    public function show($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            header("Location: home.php");
            exit();
        }

        $profile = UserProfile::findBy('user_id', $userId);
        $experiences = Experience::findByUserId($userId);
        $educations = Education::findByUserId($userId);
        $activities = Answer::findRecentByUserId($userId);

        $this->view('users.profile', compact(
            'user',
            'profile',
            'experiences',
            'educations',
            'activities'
        ));
    }

    public function edit()
    {
        $userId = $_SESSION['user_id'];
        $user = User::find($userId);
        $profile = UserProfile::findBy('user_id', $userId);

        if (!$profile) {
            $profile = new UserProfile(['user_id' => $userId]);
        }

        $this->view('users.edit', compact('user', 'profile'));
    }

    public function update()
    {
        $userId = $_SESSION['user_id'];
        $user = User::find($userId);
        $user->update(['first_name' => $_POST['first_name'] ?? $user->first_name]);

        $profile = UserProfile::findBy('user_id', $userId);
        $profileData = [
            'surname' => $_POST['surname'] ?? null,
            'headline' => $_POST['headline'] ?? null,
            'position' => $_POST['position'] ?? null,
            'company' => $_POST['company'] ?? null,
            'bio' => $_POST['bio'] ?? null
        ];

        if ($profile) {
            $profile->update($profileData);
        } else {
            UserProfile::create(array_merge(['user_id' => $userId], $profileData));
        }

        $_SESSION['success'] = 'Profile updated successfully!';
        header("Location: profile.php?id=" . $userId);
        exit();
    }
}