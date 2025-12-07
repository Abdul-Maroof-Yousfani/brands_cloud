<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    protected $connection =  'mysql2';
    protected $guarded = ["id", "created_at", "updated_at"];
    protected $table = 'delivery_note';
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function customer() {
        return $this->belongsTo(Customer::class, "buyers_id");
    }
}
