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
}