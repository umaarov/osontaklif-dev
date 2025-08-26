<?php

class ProfessionSeeder
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    private function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text ?: 'n-a');
    }

    public function run()
    {
        $professions = [
            ['name' => 'Java', 'is_active' => true],
            ['name' => 'Python', 'is_active' => true],
            ['name' => 'PHP', 'is_active' => true],
            ['name' => 'Android', 'is_active' => true],
            ['name' => 'iOS', 'is_active' => true],
            ['name' => 'Flutter', 'is_active' => true],
            ['name' => 'Node.js', 'is_active' => true],
            ['name' => 'Frontend', 'is_active' => true],
            ['name' => 'DevOps', 'is_active' => true],
            ['name' => 'CSharp', 'is_active' => false],
            ['name' => 'Golang', 'is_active' => true],
            ['name' => 'SQL', 'is_active' => true],
            ['name' => 'C++', 'is_active' => false],
            ['name' => 'Quality Assurance', 'is_active' => false],
            ['name' => 'Project Manager', 'is_active' => true],
            ['name' => 'SEO', 'is_active' => false],
        ];

        $stmt = $this->db->prepare(
            "INSERT INTO professions (name, is_active, slug, created_at, updated_at) VALUES (?, ?, ?, ?, ?)"
        );

        foreach ($professions as $data) {
            $slug = $this->slugify($data['name']);
            $now = date('Y-m-d H:i:s');
            $stmt->execute([$data['name'], $data['is_active'], $slug, $now, $now]);
        }
    }
}