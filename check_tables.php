<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

try {
    $tables = \DB::connection('mysql2')->select('SHOW TABLES');
    print_r($tables);
} catch (\Exception $e) {
    echo $e->getMessage();
}
