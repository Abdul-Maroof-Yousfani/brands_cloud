<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

try {
    $columns = Schema::connection('mysql2')->getColumnListing('purchase_return');
    print_r($columns);
    $data = \DB::connection('mysql2')->table('purchase_return')->limit(5)->get();
    print_r($data);
} catch (\Exception $e) {
    echo $e->getMessage();
}
