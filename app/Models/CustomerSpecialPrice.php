<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSpecialPrice extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'customer_special_prices';
    protected $fillable = [
        'customer_id', 'product_id', 'product_code', 'mrp_price', 'sale_price', 'status'
    ];}
