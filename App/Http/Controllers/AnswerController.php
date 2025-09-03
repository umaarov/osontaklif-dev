<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Core\Controller;

class AnswerController extends Controller
{
    public function store()
    {
        $questionId = $_POST['question_id'] ?? null;
        $professionId = $_POST['profession_id'] ?? null;

        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }

        $content = $_POST['content'] ?? '';
        if (empty(trim($content)) || !$questionId || !$professionId) {
            $_SESSION['error'] = 'An error occurred. Please try again.';
            exit();
        }

        $sql = "INSERT INTO answers (question_id, user_id, content, created_at, updated_at) VALUES (:question_id, :user_id, :content, NOW(), NOW())";
        $stmt = Answer::db()->prepare($sql);
        $stmt->execute([
            ':question_id' => $questionId,
            ':user_id' => $_SESSION['user_id'],
            ':content' => $content,
        ]);

        $_SESSION['success'] = 'Your answer has been posted!';
        header('Location: question.php?id=' . $questionId . '&pid=' . $professionId);
        exit();
    }
}