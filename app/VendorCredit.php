<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorCredit extends Model
{
    protected $connection = "mysql2";
    protected $table = "vendor_credits";
    protected $guarded = ["id", "created_at", "updated_at"];
}