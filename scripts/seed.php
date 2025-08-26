<?php

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

$seederName = $argv[1] ?? null;

if (!$seederName) {
    echo "Usage: php scripts/seed.php [seeder_name|all]\n";
    echo "Available seeders: ProfessionSeeder, InterviewSeeder, QuestionSeeder\n";
    exit(1);
}

$seeders = [
    'ProfessionSeeder',
    'InterviewSeeder',
    'QuestionSeeder'
];

function runSeeder($seederClass)
{
    $file = BASE_PATH . '/scripts/seeders/' . $seederClass . '.php';
    if (file_exists($file)) {
        require_once $file;
        echo "Running {$seederClass}...\n";
        $seeder = new $seederClass();
        $seeder->run();
        echo "Finished {$seederClass}.\n\n";
    } else {
        echo "Error: Seeder class {$seederClass} not found.\n";
    }
}

if ($seederName === 'all') {
    foreach ($seeders as $seeder) {
        runSeeder($seeder);
    }
} else {
    if (in_array($seederName, $seeders)) {
        runSeeder($seederName);
    } else {
        echo "Error: Seeder '{$seederName}' is not a valid seeder.\n";
    }
}
?>
