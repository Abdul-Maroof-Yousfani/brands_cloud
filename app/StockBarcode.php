<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockBarcode extends Model
{
    protected $table = 'stock_barcodes';
    protected $connection = 'mysql2';

    protected $fillable = [
        'product_id',
        'voucher_no',
        'voucher_type',
        'type',      // e.g., 1 for Stock-in
        'barcode',
        // Add any additional fields here if needed
    ];
    //
}
