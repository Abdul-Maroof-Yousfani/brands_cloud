<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model{
    protected $connection = "mysql2";
    protected $table = 'purchase_request';
    protected $fillable = ['slip_no','purchase_request_no','purchase_request_date','department_id','supplier_id','description','purchase_request_status','status','date','time','username','approve_username','delete_username','sales_tax_acc_id','grn_data_status','sales_tax'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo(SubDepartment::class, 'agent', 'id');
    }
}
