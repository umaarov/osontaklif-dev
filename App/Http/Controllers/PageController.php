<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Profession;
use App\Models\Question;
use Core\Cache;
use Core\Controller;
use Database;
use PDO;

class PageController extends Controller
{
    public function home()
    {
        $cache = new Cache();
        $cacheKey = 'professions_home_page';

        $professions = $cache->remember($cacheKey, 3600, function () {
            return Profession::query("
            SELECT p.*, COUNT(q.id) as questions_count
            FROM professions p
            LEFT JOIN questions q ON p.id = q.profession_id
            WHERE p.is_active = 1
            GROUP BY p.id
        ");
        });

        $this->view('pages.home', compact('professions'));
    }

    public function profession($name)
    {

        $profession = Profession::findOneBy('slug', $name);
        if (!$profession) {
            header("Location: home.php");
            exit();
        }

        $metaTitle = "{$profession->name} Interview Questions - " . APP_NAME;
        $metaDescription = "Top interview questions for the {$profession->name} role. Prepare for your tech interview with our curated list.";
        $canonicalUrl = APP_URL . "/profession.php?name=" . urlencode($profession->slug);

        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'desc';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 100;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM questions WHERE profession_id = :profession_id";
        $params = [':profession_id' => $profession->id];

        if ($search) {
            $sql .= " AND question LIKE :search";
            $params[':search'] = "%$search%";
        }

        $countSql = str_replace('SELECT *', 'SELECT COUNT(*)', $sql);
        $totalStmt = Profession::db()->prepare($countSql);
        $totalStmt->execute($params);
        $total = $totalStmt->fetchColumn();
        $totalPages = ceil($total / $perPage);

        $sql .= " ORDER BY chance " . ($sort === 'asc' ? 'ASC' : 'DESC');
        $sql .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = $perPage;
        $params[':offset'] = $offset;

        $stmt = Profession::db()->prepare($sql);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('pages.profession', compact(
                'profession',
                'questions', 'search', 'sort', 'page', 'totalPages', 'total'
                , 'metaTitle', 'metaDescription', 'canonicalUrl'
            )
        );
    }


    public function question($id, $professionId = null)
    {
        $question = Question::find($id);
        $profession = null;
        if ($professionId) {
            $profession = Profession::find($professionId);
        }
        $this->view('pages.question', compact('question', 'profession'));
    }

    public function mock()
    {
        $positions = Profession::query("SELECT * FROM professions WHERE is_active = 1 ORDER BY name");

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 100;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT i.*, p.name as profession_name FROM interviews i JOIN professions p ON i.profession_id = p.id WHERE 1=1";
        $params = [];

        if (!empty($_GET['position'])) {
            $sql .= " AND i.profession_id = :position";
            $params[':position'] = $_GET['position'];
        }
        if (!empty($_GET['grade'])) {
            $sql .= " AND i.grade = :grade";
            $params[':grade'] = $_GET['grade'];
        }

        $countSql = preg_replace('/SELECT i\.\*, p\.name as profession_name/', 'SELECT COUNT(*)', $sql);
        $totalStmt = Interview::db()->prepare($countSql);
        $totalStmt->execute($params);
        $total = $totalStmt->fetchColumn();
        $totalPages = ceil($total / $perPage);

        $sql .= " ORDER BY i.created_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $perPage;
        $params[':offset'] = $offset;

        $stmt = Interview::db()->prepare($sql);
        foreach ($params as $key => &$val) {
            if (is_int($val)) {
                $stmt->bindParam($key, $val, PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $val, PDO::PARAM_STR);
            }
        }
        $stmt->execute();

        $interviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('pages.mock', compact('interviews', 'positions', 'page', 'totalPages'));
    }

    public function requirements()
    {
        $professions = Profession::query("SELECT * FROM professions WHERE is_active = 1 ORDER BY name");
        $this->view('pages.requirements', compact('professions'));
    }

    public function requirement_show($name)
    {
        $profession = Profession::findOneBy('slug', $name);
        if (!$profession) {
            header("Location: requirements.php");
            exit();
        }

        $sort = $_GET['sort'] ?? 'desc';
        $validatedSort = in_array($sort, ['asc', 'desc']) ? $sort : 'desc';

        $search = $_GET['search'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM profession_skills WHERE profession_id = :id AND skill_name != '_total_processed'";
        $params = [':id' => $profession->id];

        if ($search) {
            $sql .= " AND skill_name LIKE :search";
            $params[':search'] = "%$search%";
        }

        $totalSkillsStmt = Profession::db()->prepare(str_replace('*', 'COUNT(*)', $sql));
        $totalSkillsStmt->execute($params);
        $totalSkills = $totalSkillsStmt->fetchColumn();

        $sql .= " ORDER BY count $validatedSort LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = Profession::db()->prepare($sql);
        foreach ($params as $key => &$val) {
            if (is_int($val)) {
                $stmt->bindParam($key, $val, PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $val, PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $metaRecordStmt = Profession::db()->prepare("SELECT * FROM profession_skills WHERE profession_id = :id AND skill_name = '_total_processed'");
        $metaRecordStmt->execute([':id' => $profession->id]);
        $metaRecord = $metaRecordStmt->fetch();
        $totalProcessed = $metaRecord ? $metaRecord['count'] : 0;
        $lastUpdated = $metaRecord ? $metaRecord['last_updated'] : $profession->updated_at;
        $hasMoreSkills = $totalSkills > ($offset + $limit);
        $validatedSearch = $search;

        $this->view('pages.requirement_show', compact(
            'profession', 'skills', 'lastUpdated', 'validatedSort', 'validatedSearch',
            'totalProcessed', 'page', 'limit', 'totalSkills', 'hasMoreSkills'
        ));
    }

    public function about()
    {
        $this->view('pages.about');
    }

    public function terms()
    {
        $this->view('pages.terms');
    }

    public function sponsorship()
    {
        $this->view('pages.sponsorship');
    }

    public function ads()
    {
        $this->view('pages.ads');
    }

    public function sitemap()
    {
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);

        $db = Database::getInstance()->getConnection();
        $baseUrl = rtrim(APP_URL, '/');

        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        $createUrlEntry = function ($path, $lastmod, $priority = '0.80') use ($baseUrl) {
            $loc = $baseUrl . '/' . ltrim($path, '/');
            $lastmodDate = date('Y-m-d', strtotime($lastmod));
            echo '  <url>' . PHP_EOL;
            echo '    <loc>' . htmlspecialchars($loc) . '</loc>' . PHP_EOL;
            echo '    <lastmod>' . $lastmodDate . '</lastmod>' . PHP_EOL;
            echo '    <priority>' . $priority . '</priority>' . PHP_EOL;
            echo '  </url>' . PHP_EOL;
        };

        $today = date('Y-m-d H:i:s');
        $createUrlEntry('/', $today, '1.00');
        $createUrlEntry('/mock.php', $today, '0.90');
        $createUrlEntry('/requirements.php', $today, '0.90');
        $createUrlEntry('/about.php', $today, '0.70');
        $createUrlEntry('/terms.php', $today, '0.50');
        $createUrlEntry('/sponsorship.php', $today, '0.50');
        $createUrlEntry('/ads.php', $today, '0.50');

        $professionsStmt = $db->prepare("SELECT slug, updated_at FROM professions WHERE is_active = 1");
        $professionsStmt->execute();
        while ($row = $professionsStmt->fetch(PDO::FETCH_ASSOC)) {
            $createUrlEntry('/profession.php?name=' . $row['slug'], $row['updated_at'], '0.90');
            $createUrlEntry('/requirements.php?name=' . $row['slug'], $row['updated_at'], '0.90');
        }

        $questionsStmt = $db->prepare(
            "SELECT q.id, q.updated_at, p.slug as profession_slug
         FROM questions q
         JOIN professions p ON q.profession_id = p.id
         WHERE p.is_active = 1"
        );
        $questionsStmt->execute();
        while ($row = $questionsStmt->fetch(PDO::FETCH_ASSOC)) {
            $createUrlEntry('/question.php?id=' . $row['id'] . '&pid=' . $row['profession_slug'], $row['updated_at'], '0.80');
        }

        echo '</urlset>';
        exit;
    }
}