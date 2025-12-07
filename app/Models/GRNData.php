<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GRNData extends Model{
    protected $connection = "mysql2";
    protected $table = 'grn_data';
    protected $fillable = ['grn_no','grn_date','category_id','sub_item_id','purchaseRequestQty','rate','subTotal','receivedQty','status','grn_status','username','date','time','approve_username','delete_username','barcodes','received_type'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
