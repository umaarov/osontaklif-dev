<?php

namespace App\Services;

use App\Models\Profession;
use Database;
use Exception;

class HhService
{
    private $baseUrl = 'https://api.hh.uz/vacancies';
    private $areasUrl = 'https://api.hh.uz/areas';
    private $uzbekistanAreaId = '97';
    private $perPage = 100;
    private $maxPages = 20;
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    final public function fetchSkillsForProfession(Profession $profession): bool
    {
        try {
            echo "Starting to fetch skills for profession: {$profession->name}\n";

            $searchKeyword = urlencode($profession->name);
            $startTime = microtime(true);

            $uzbekistanAreas = $this->fetchUzbekistanAreas();

            if (empty($uzbekistanAreas)) {
                error_log("No areas found for Uzbekistan. Cannot continue.");
                return false;
            }

            $filteredVacancies = [];
            $totalFound = 0;
            $totalProcessed = 0;

            for ($page = 0; $page < $this->maxPages; $page++) {
                $searchUrl = "{$this->baseUrl}?text={$searchKeyword}&per_page={$this->perPage}&page={$page}&area={$this->uzbekistanAreaId}";
                $searchResults = $this->makeApiRequest($searchUrl);

                if (!$searchResults || empty($searchResults['items'])) {
                    echo "No more results found or error occurred on page $page\n";
                    break;
                }

                if ($page === 0 && isset($searchResults['found'])) {
                    $totalFound = $searchResults['found'];
                    echo "Found {$totalFound} total vacancies for {$profession->name} in Uzbekistan\n";
                    if ($totalFound == 0) {
                        return false;
                    }
                }

                foreach ($searchResults['items'] as $vacancy) {
                    $totalProcessed++;
                    if (!isset($vacancy['id'], $vacancy['area']['id'], $vacancy['url'])) {
                        continue;
                    }

                    if ($this->isAreaInUzbekistan($vacancy['area']['id'], $uzbekistanAreas)) {
                        $vacancyDetails = $this->fetchVacancyDetails($vacancy['id'], $vacancy['url']);
                        if ($vacancyDetails) {
                            $filteredVacancies[] = $vacancyDetails;
                        }
                    }
                }

                if ($page < $this->maxPages - 1 && $totalProcessed < $totalFound) {
                    usleep(500000);
                } else {
                    break;
                }
            }

            $skillsCount = $this->countSkills($filteredVacancies);
            $this->updateSkillsInDatabase($profession, $skillsCount, $totalProcessed);

            $executionTime = microtime(true) - $startTime;
            echo "Completed fetching skills for {$profession->name} in " . round($executionTime, 2) . " seconds\n";

            return true;

        } catch (Exception $e) {
            error_log("Error fetching skills for profession {$profession->name}: " . $e->getMessage());
            return false;
        }
    }

    private function makeApiRequest(string $url): ?array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'OsonTaklif/1.0 (+https://osontaklif.uz)');
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false || $httpCode !== 200) {
            $error = curl_error($ch);
            error_log("API request failed: $error, HTTP code: $httpCode for URL: $url");
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        return json_decode($response, true);
    }

    private function fetchUzbekistanAreas(): array
    {
        $areaData = $this->makeApiRequest("{$this->areasUrl}/{$this->uzbekistanAreaId}");
        if (!$areaData || !isset($areaData['areas'])) {
            return [];
        }
        $areas = [$this->uzbekistanAreaId];
        foreach ($areaData['areas'] as $area) {
            $areas[] = $area['id'];
        }
        return $areas;
    }

    private function isAreaInUzbekistan(string $areaId, array $uzbekistanAreas): bool
    {
        return in_array($areaId, $uzbekistanAreas);
    }

    private function fetchVacancyDetails(string $vacancyId, string $vacancyUrl): ?array
    {
        $vacancyData = $this->makeApiRequest($vacancyUrl);
        if (!$vacancyData) return null;

        $keySkills = [];
        if (isset($vacancyData['key_skills']) && is_array($vacancyData['key_skills'])) {
            $keySkills = array_column($vacancyData['key_skills'], 'name');
        }

        return ['id' => $vacancyId, 'url' => $vacancyUrl, 'key_skills' => $keySkills];
    }

    private function countSkills(array $vacancies): array
    {
        $skillCount = [];
        foreach ($vacancies as $vacancy) {
            foreach ($vacancy['key_skills'] as $skill) {
                $skillCount[$skill] = ($skillCount[$skill] ?? 0) + 1;
            }
        }
        arsort($skillCount);
        return $skillCount;
    }

    private function updateSkillsInDatabase(Profession $profession, array $skillsCount, int $totalProcessed): void
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM profession_skills WHERE profession_id = ?");
            $stmt->execute([$profession->id]);

            $now = date('Y-m-d H:i:s');
            $insertSql = "INSERT INTO profession_skills (profession_id, skill_name, count, last_updated, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($insertSql);
            $stmt->execute([$profession->id, '_total_processed', $totalProcessed, $now, $now, $now]);

            foreach ($skillsCount as $skill => $count) {
                $stmt->execute([$profession->id, $skill, $count, $now, $now, $now]);
            }
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("DB Error for {$profession->name}: " . $e->getMessage());
        }
    }
}
