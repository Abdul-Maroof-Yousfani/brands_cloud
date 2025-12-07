<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class PackingData extends Model
{
	protected $connection = 'mysql2';

     protected $fillable = [
        'packing_id', 'machine_proccess_data_id', 'bundle_no', 'qty', 'status', 'username',
    ];
    //
}
