<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'customer_group';
    protected $fillable = ['customer_group', 'status'];
}
