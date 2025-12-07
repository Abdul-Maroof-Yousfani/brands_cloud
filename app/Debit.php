<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{
    
    protected $connection = "mysql2";
    protected $table = "debits";
    protected $guarded = ["id", "created_at","updated_at"];
}
