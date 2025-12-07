<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    protected $fillable = ['name', 'description', 'permit_key'];

    public function roles()
    {
        return $this->belongsToMany(Roles::class);
    }
}
