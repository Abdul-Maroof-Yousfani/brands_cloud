<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $connection = "mysql2";
    protected $table = "branch";
    // protected $guarded = ["id"];
}
