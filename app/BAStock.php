<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BAStock extends Model
{
    protected $table = 'ba_stock';
    protected $primaryKey = 'id';
    protected  $guarded = [];
    protected $connection = 'mysql2';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Models\Customer::class, 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Models\Subitem::class, 'sub_item_id');
    }
}
