<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'brands';
    protected $guarded = [];


    public function subitems()
    {
        return $this->hasMany(Subitem::class);
    }

// Brand.php
public function principalGroup()
{
    // Correct relation
    return $this->belongsTo(ProductsPrincipalGroup::class, 'principal_group_id', 'id');
}

}
