<?php

namespace App;

use App\Models\Customer;
use App\Models\Sales_Order_Data;
use Illuminate\Database\Eloquent\Model;

class Sales_Return extends Model
{
     protected $table = 'sales_return';
    protected $connection =  'mysql2';
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    // protected $primaryKey = 'id';
    public $timestamps = false;

   


    public function customer()
    {
        return $this->hasOne(Customer::class,'id','buyers_id');
    }
    public function saleReturnData()
    {
        return $this->hasMany(Sales_Order_Data::class,'master_id','id');
    }
}
