<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Core\Controller;

class AnswerController extends Controller
{
    public function store($questionId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        $content = $_POST['content'] ?? '';
        if (empty(trim($content))) {
            $_SESSION['error'] = 'Answer content cannot be empty.';
            header('Location: /questions/' . $questionId);
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
        header('Location: /questions/' . $questionId);
        exit();
    }
}