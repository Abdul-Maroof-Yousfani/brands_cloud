<?php
// app/Models/StockTransferRequestItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_transfer_request_id',
        'product_id',
        'sku',
        'quantity',
        'unit_price',
        'total_price',
        'warehouse_from_id',
        'warehouse_to_id',
        'batch_code',
        'remarks'
    ];

    // Relationships
    public function request()
    {
        return $this->belongsTo(StockTransferRequest::class, 'stock_transfer_request_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouseFrom()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_from_id');
    }

    public function warehouseTo()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_to_id');
    }
}