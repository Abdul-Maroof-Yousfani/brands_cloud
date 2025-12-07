<?php

namespace App;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class BAFormation extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'b_a_formations';

    protected $fillable = [
        'ba_no',
        'employee_id',
        'customer_id',
        'brands_ids',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
