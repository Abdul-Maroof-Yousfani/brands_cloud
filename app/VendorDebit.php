<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorDebit extends Model
{
    protected $connection = "mysql2";
    protected $table = "vendor_debits";
    protected $guarded = ["id", "created_at", "updated_at"];
}
