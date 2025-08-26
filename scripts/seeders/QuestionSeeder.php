<?php
class QuestionSeeder {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function run() {
        $questionPath = BASE_PATH . '/database/data/question';
        $contentPath = $questionPath . '/content';

        $professionStmt = $this->db->prepare("SELECT id FROM professions WHERE name = ?");

        $insertStmt = $this->db->prepare(
            "INSERT INTO questions (profession_id, question, content, chance, tag, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $files = glob($questionPath . '/*.json');

        foreach ($files as $file) {
            $professionName = pathinfo($file, PATHINFO_FILENAME);
            $professionStmt->execute([ucfirst($professionName)]);
            $profession = $professionStmt->fetch();

            if (!$profession) {
                echo "Warning: Profession not found for {$professionName}.json, skipping.\n";
                continue;
            }
            $professionId = $profession['id'];

            $questions = json_decode(file_get_contents($file), true);
            foreach ($questions as $q) {
                $contentFilePath = $contentPath . '/question_' . $q['id'] . '.html';
                $content = file_exists($contentFilePath) ? file_get_contents($contentFilePath) : '';
                $tags = implode(',', $q['tags'] ?? []);
                $now = date('Y-m-d H:i:s');

                $insertStmt->execute([
                    $professionId,
                    $q['question'],
                    $content,
                    $q['chance'] ?? 0,
                    $tags,
                    $now,
                    $now
                ]);
            }
        }
    }
}
