<?php

class InterviewSeeder {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function run() {
        $jsonPath = BASE_PATH . '/database/data/interviews.json';
        if (!file_exists($jsonPath)) {
            echo "Error: interviews.json not found in 'database/data'.\n";
            return;
        }

        $json = file_get_contents($jsonPath);
        $interviews = json_decode($json, true);

        $stmt = $this->db->prepare(
            "INSERT INTO interviews (title, link, profession_id, grade, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)"
        );

        foreach ($interviews as $interview) {
            $now = date('Y-m-d H:i:s');
            $stmt->execute([
                $interview['title'],
                $interview['link'],
                $interview['profession_id'],
                $interview['grade'],
                $now,
                $now
            ]);
        }
    }
}
