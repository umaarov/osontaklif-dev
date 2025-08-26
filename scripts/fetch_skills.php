<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

use App\Services\HhService;
use App\Models\Profession;

$professionName = isset($argv[1]) ? $argv[1] : null;

$hhService = new HhService();

if ($professionName) {
    $profession = Profession::findOneBy('name', $professionName);
    if (!$profession) {
        echo "Error: Profession '{$professionName}' not found.\n";
        exit(1);
    }

    echo "Fetching skills for profession: {$profession->name}\n";
    $success = $hhService->fetchSkillsForProfession($profession);

    if ($success) {
        echo "Successfully fetched skills for {$profession->name}\n";
        exit(0);
    } else {
        echo "Failed to fetch skills for {$profession->name}\n";
        exit(1);
    }
} else {
    $professions = Profession::all();
    $total = count($professions);
    echo "Fetching skills for all {$total} professions...\n";

    $successful = 0;
    $failed = 0;

    foreach ($professions as $i => $profession) {
        echo "Processing {$profession->name} (" . ($i + 1) . "/{$total})...\n";
        $success = $hhService->fetchSkillsForProfession($profession);

        if ($success) {
            $successful++;
        } else {
            $failed++;
        }
        sleep(2);
    }

    echo "\nFinished fetching skills for all professions.\n";
    echo "Successful: {$successful}, Failed: {$failed}\n";

    exit($failed === 0 ? 0 : 1);
}
