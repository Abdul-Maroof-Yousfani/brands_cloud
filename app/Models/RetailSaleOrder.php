<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RetailSaleOrder extends Model
{
    protected $table = 'retail_sale_orders';
    protected $connection = 'mysql2';

    protected $guarded = [];
      

    public function distributor()
    {
        return $this->belongsTo(Customer::class,'distributor_id');
    }

    public function details()
    {
        return $this->hasMany(RetailSaleOrderDetail::class);
    }
}
