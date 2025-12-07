<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	protected $table = 'customers';
	protected $connection = 'mysql2';
	protected $fillable = ['acc_id', 'type', 'customer_type', 'name', 'company_name', 'address', 'country', 'province', 'city', 'contact', 'email', 'status', 'action', 'username', 'date', 'time', 'branch_id'];
	protected $primaryKey = 'id';
	public $timestamps = false;

	public function specialPrices()
{
    return $this->hasMany(SpecialPrice::class, 'customer_id');
}

public function brands()
{
    return $this->belongsToMany(Brand::class, 'special_prices', 'customer_id', 'brand_id')
                ->withPivot('discount');
}
}
