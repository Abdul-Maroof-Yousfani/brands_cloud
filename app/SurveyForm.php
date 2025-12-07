<?php

namespace App;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Subitem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SurveyForm extends Model
{
    protected $table = 'surveyform';

    // Specify the database connection
    protected $connection = 'mysql2';
    protected $guarded = [];


    public function distributor()
    {
        return $this->belongsTo(Customer::class,'distributor_id');
    }

    public function currentlyUsingBrand()
    {
        return $this->belongsTo(Brand::class, 'currently_using_brand_id');
    }

    // Relationship with Brand model for second currently using brand
    public function currentlyUsingBrand2()
    {
        return $this->belongsTo(Brand::class, 'currently_using_brand_2_id');
    }

    public function product()
    {
        return $this->belongsTo(Subitem::class, 'product_id'); // Ensure 'Subitem' model is correctly set
    }
}
