<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialPrice extends Model
{
    protected $connection = 'mysql2';
	protected $table = 'special_prices';

      protected $fillable = [
        'customer_id',
        'brand_id',
        'discount',
    ];
}
