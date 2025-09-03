<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Core\AuthService;
use Core\Controller;

class ExperienceController extends Controller
{
    public function store()
    {
        $user = AuthService::user();
        $data = [
            'user_id' => $user->id,
            'job_title' => $_POST['job_title'],
            'company_name' => $_POST['company_name'],
            'start_date' => $_POST['start_date'],
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'description' => $_POST['description']
        ];
        Experience::create($data);
        $_SESSION['success'] = 'Experience added successfully.';
        header("Location: profile.php?user=" . $user->username);
        exit();
    }

    public function update($id)
    {
        $user = AuthService::user();
        $experience = Experience::find($id);
        if ($experience && $experience->user_id == $user->id) {
            $data = [
                'job_title' => $_POST['job_title'],
                'company_name' => $_POST['company_name'],
                'start_date' => $_POST['start_date'],
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'description' => $_POST['description']
            ];
            $experience->update($data);
            $_SESSION['success'] = 'Experience updated successfully.';
        } else {
            $_SESSION['error'] = 'Unauthorized action.';
        }
        header("Location: profile.php?user=" . $user->username);
        exit();
    }

    public function destroy($id)
    {
        $user = AuthService::user();
        $experience = Experience::find($id);
        if ($experience && $experience->user_id == $user->id) {
            $experience->delete();
            $_SESSION['success'] = 'Experience deleted successfully.';
        } else {
            $_SESSION['error'] = 'Unauthorized action.';
        }
        header("Location: profile.php?user=" . $user->username);
        exit();
    }
}