<?php

namespace App;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RetailSaleOrderReturn extends Model
{
    protected $table = 'retail_sale_order_returns';
    protected $connection = 'mysql2';
    protected $guarded = [];

   
    
    public function returnDetails()
    {
        return $this->hasMany(RetailSaleOrderReturnDetail::class, 'retail_sale_order_return_id');
    }

    public function distributor()
    {
        return $this->belongsTo(Customer::class,'distributor_id');
    }

}
