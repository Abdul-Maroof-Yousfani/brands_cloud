<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class QaTest extends Model
{
    protected $connection = 'mysql2';
    protected $fillable = [
        'name',
        'operator',
        'status',
        'username'
    ];
}
