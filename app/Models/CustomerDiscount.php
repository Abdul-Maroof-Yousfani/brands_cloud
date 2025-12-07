<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDiscount extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'customer_discounts';
    protected $fillable = [
        'status',
        'customer_id',
        'brand_id',
        'product_id',
        'discount_percentage',
        'discount_price',
    ];
}
