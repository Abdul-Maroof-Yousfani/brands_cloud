<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolesHasPermission extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    // Add relationships if needed
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
