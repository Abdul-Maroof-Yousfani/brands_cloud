<?php

namespace App;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Merchandise extends Model
{
    public $table ='merchandise';
    public $connection ='mysql2';
    protected $guarded = [];
    protected $primarykey = 'id';


    
    public function distributor()
    {
        return $this->belongsTo(Customer::class,'distributor_id');
    }
}
