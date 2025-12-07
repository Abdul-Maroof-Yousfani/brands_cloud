<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProductClassification extends Model
{
    public $connection = "mysql2";
    protected $table = "product_classifications";
    public $timestamps = false;
}
