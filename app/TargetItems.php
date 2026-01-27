<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetItems extends Model
{
    protected $table = "target_items";
    protected $connection = "mysql2";
    protected $guarded = [
        "id",
        "created_at",
        "updated_at"
    ];
}
