<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaLocationModel extends Model
{


  protected $table = 'ba_locations';

      protected $fillable = [
        'ba_id',
        'location_name',
        'latitude',
        'longitude',
        'radius'
    ];


    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
