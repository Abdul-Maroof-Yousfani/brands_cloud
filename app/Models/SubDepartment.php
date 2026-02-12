<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model{
	protected $table = 'sub_department';
	protected $fillable = ['department_id','sub_department_name','phone_number','status','username','action','date','time','company_id','territory_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;
}
