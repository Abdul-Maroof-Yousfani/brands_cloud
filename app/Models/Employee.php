<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model{
	protected $table = 'employees';
	protected $guarded = ["id", "created_at", "updated_at"];
	protected $connection = "mysql2";
	protected $primaryKey = 'id';
	public $timestamps = false;

 
}

