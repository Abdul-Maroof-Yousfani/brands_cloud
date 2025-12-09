<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsPrincipalGroup extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'products_principal_group';
    protected $fillable = ['products_principal_group', 'status'];
}



