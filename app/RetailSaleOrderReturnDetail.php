<?php

namespace App;

use App\Models\Brand;
use App\Models\Subitem;
use Illuminate\Database\Eloquent\Model;

class RetailSaleOrderReturnDetail extends Model
{
    protected $table = 'retail_sale_order_return_details';
    protected $connection = 'mysql2';
    protected $guarded = [];

    public function retailSaleOrderReturn()
    {
        return $this->belongsTo(RetailSaleOrderReturn::class, 'retail_sale_order_return_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Subitem::class, 'product_id'); // Ensure 'Subitem' model is correctly set
    }
}
