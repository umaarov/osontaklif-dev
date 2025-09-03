<?php

namespace App\Models;

use Core\Model;
use PDO;

class Answer extends Model
{
    protected static $table = 'answers';

    public static function findByQuestionId(int $questionId)
    {
        $sql = "
            SELECT 
                a.*, 
                u.first_name,
                u.username,
                up.surname,
                up.avatar_url
            FROM answers a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN user_profiles up ON u.id = up.user_id
            WHERE a.question_id = :question_id
            ORDER BY a.created_at DESC
        ";
        $stmt = self::db()->prepare($sql);
        $stmt->execute([':question_id' => $questionId]);

        return self::processResults($stmt);
    }

    public static function findRecentByUserId(int $userId, int $limit = 5)
    {
        $sql = "
        SELECT a.*, q.question 
        FROM answers a
        JOIN questions q ON a.question_id = q.id
        WHERE a.user_id = :user_id
        ORDER BY a.created_at DESC
        LIMIT :limit
    ";

        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        return $models;
    }

    private static function processResults($stmt)
    {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $models = [];
        foreach ($results as $result) {
            $models[] = new static($result);
        }
        return $models;
    }
}