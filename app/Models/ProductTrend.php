<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTrend extends Model
{
    public $connection = "mysql2";
    protected $table = "product_trends";
    public $timestamps = false;
}
