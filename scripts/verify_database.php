<?php

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$connection = Illuminate\Support\Facades\DB::connection();
$connection->select('SELECT 1 AS connected');
echo 'Connection: '.$connection->getDriverName().' / '.$connection->getDatabaseName().PHP_EOL;
foreach (['ecosystems', 'organisms', 'ecosystem_organism', 'quiz_questions', 'quiz_puzzles', 'badges', 'learning_rules'] as $table) {
    echo $table.': '.$connection->table($table)->count().PHP_EOL;
}
