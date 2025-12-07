<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $fillable = ['name','description', 'status'];

    public function permissions()
    {
        return $this->belongsToMany(Permissions::class);
    }


    public function user()
    {
        return $this->hasOne(User::class, 'ba_role_id', 'id');
    }
}
