<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailSaleOrderDetail extends Model
{
    protected $table = 'retail_sale_order_details';
    protected $connection = 'mysql2';

    protected $fillable = [
        'retail_sale_order_id',
        'brand_id',
        'product_id',
        'qty',
    ];

    public function order()
    {
        return $this->belongsTo(RetailSaleOrder::class, 'retail_sale_order_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Subitem::class, 'product_id'); // Ensure 'Subitem' model is correctly set
    }
}
