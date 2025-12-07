<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialPrice extends Model
{

     protected $table = 'special_prices';
      protected $fillable = [
        'customer_id',
        'brand_id',
        'discount',
    ];
}
