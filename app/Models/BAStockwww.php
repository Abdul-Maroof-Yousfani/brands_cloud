<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BAStock extends Model
{
    protected $table = 'ba_stock';
    protected $primaryKey = 'id';
    protected  $guarded = [];
    protected $connection = 'mysql2';
    public $timestamps = false;
}
