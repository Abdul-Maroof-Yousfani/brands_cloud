<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaTargets extends Model
{

    protected $connection = 'mysql2';
    protected $table = 'ba_targets';

    protected $fillable = [
        'employee_id',
        'customer_id',
        'brands',
        'start_date',
        'end_date',
        'target_qty',
        'status',
    ];

    protected $casts = [
        'brands' => 'array',
    ];
}
