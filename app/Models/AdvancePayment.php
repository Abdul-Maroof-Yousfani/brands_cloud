<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
     protected $table = 'advance_payments';
     protected $connection = 'mysql2';
     protected $guarded = [];
}
