<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleTax extends Model
{
    protected $table = 'sale_taxes';
    protected $connection = 'mysql2';

    protected $fillable = [
        'name',
        'discount_percentage',
        'status',
        
    ];
}
