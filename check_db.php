<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    $columns = Schema::connection('mysql2')->getColumnListing('new_purchase_voucher_data');
    print_r($columns);
    
    $master_columns = Schema::connection('mysql2')->getColumnListing('new_purchase_voucher');
    print_r($master_columns);
} catch (\Exception $e) {
    echo $e->getMessage();
}
