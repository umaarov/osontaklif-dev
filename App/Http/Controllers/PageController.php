<?php

namespace App\Http\Controllers;

use Core\Controller;
use App\Models\Profession;
use App\Models\Question;
use App\Models\Interview;

class PageController extends Controller
{
    public function home()
    {
        $professions = Profession::query("
            SELECT p.*, COUNT(q.id) as questions_count
            FROM professions p
            LEFT JOIN questions q ON p.id = q.profession_id
            WHERE p.is_active = 1
            GROUP BY p.id
        ");
        $this->view('pages.home', compact('professions'));
    }

    public function profession($slug)
    {
        $profession = Profession::findOneBy('slug', $slug);
        if (!$profession) {
            header("Location: " . APP_URL);
            exit();
        }

        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
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
        foreach($params as $key => &$val) {
            if(is_int($val)){
                $stmt->bindParam($key, $val, \PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $val, \PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        $questions = $stmt->fetchAll(\PDO::FETCH_CLASS, Question::class);

        $this->view('pages.profession', compact('profession', 'questions', 'search', 'sort', 'page', 'totalPages', 'total'));
    }

    public function question($id, $professionSlug = null)
    {
        $question = Question::find($id);
        $profession = null;
        if ($professionSlug) {
            $profession = Profession::findOneBy('slug', $professionSlug);
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
        foreach($params as $key => &$val) {
            if(is_int($val)){
                $stmt->bindParam($key, $val, \PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $val, \PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        $interviews = $stmt->fetchAll(\PDO::FETCH_CLASS, Interview::class);

        $this->view('pages.mock', compact('interviews', 'positions', 'page', 'totalPages'));
    }

    public function requirements()
    {
        $professions = Profession::query("SELECT * FROM professions WHERE is_active = 1 ORDER BY name");
        $this->view('pages.requirements', compact('professions'));
    }

    public function requirement_show($slug)
    {
        $profession = Profession::findOneBy('slug', $slug);
        if (!$profession) {
            header("Location: " . APP_URL . "/requirements.php");
            exit();
        }

        $name = $profession->name;

        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $sort = in_array(isset($_GET['sort']) ? $_GET['sort'] : 'desc', ['asc', 'desc']) ? $_GET['sort'] : 'desc';
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

        $sql .= " ORDER BY count $sort LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = Profession::db()->prepare($sql);
        foreach($params as $key => &$val) {
            if(is_int($val)){
                $stmt->bindParam($key, $val, \PDO::PARAM_INT);
            } else {
                $stmt->bindParam($key, $val, \PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        $skills = $stmt->fetchAll();

        $metaRecordStmt = Profession::db()->prepare("SELECT * FROM profession_skills WHERE profession_id = :id AND skill_name = '_total_processed'");
        $metaRecordStmt->execute([':id' => $profession->id]);
        $metaRecord = $metaRecordStmt->fetch();
        $totalProcessed = $metaRecord ? $metaRecord['count'] : 0;
        $lastUpdated = $metaRecord ? $metaRecord['last_updated'] : $profession->updated_at;

        $hasMoreSkills = $totalSkills > ($offset + $limit);

        $validatedSearch = $search;
        $validatedSort = $sort;

        $this->view('pages.requirement_show', compact(
            'profession', 'skills', 'lastUpdated', 'validatedSort', 'validatedSearch',
            'totalProcessed', 'page', 'limit', 'totalSkills', 'hasMoreSkills', 'name'
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
}