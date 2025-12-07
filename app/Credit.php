<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $connection = "mysql2";
    protected $table = "credits";
    protected $guarded = ["id", "created_at","updated_at"];
}
