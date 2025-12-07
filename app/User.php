<?php

namespace App;

use App\Models\Brand;
use App\Models\Customer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'users_roles');
    }

    public function ba_role()
    {
        return $this->belongsTo(Roles::class, 'ba_role_id', 'id');
    }

    public function ba_permissions()
    {
        return $this->ba_role ? $this->ba_role->permissions->pluck('permit_key')->flatten()->unique() : collect();
    }

    // Retrieve permissions via roles
    public function permissions()
    {
        return $this->roles->map(function ($role) {
            return $role->permissions;
        })->flatten()->unique();
    }

    public function locations()
{
    return $this->hasMany(BaLocationModel::class, 'ba_id'); 
}

    public function setPasswordAttribute($value)
    {        
        $this->attributes['password'] = Hash::make($value);
    }

    /** Scopes */
    // get unique no
    function scopeUniqueNo($query)
    {
     $id = $query->max('id')+1;
    return  $number = sprintf('%03d',$id);

    }


    public function generateApiToken()
    {
        $this->api_token = bin2hex(random_bytes(40)); // Generates a 40-character unique token
        $this->save();

        return $this->api_token;
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            BAFormation::class,
            'employee_id', // Foreign key on b_a_formations table
            'id',       // Foreign key on customers table
            'emp_code', // Local key on users table
            'customer_id' // Local key on b_a_formations table
        );
    }

    public function brands($customer_id)
    {
        // Get the BAFormation record for the current user
        $formation = BAFormation::where('employee_id', $this->emp_code)->where('customer_id', $customer_id)->first();
    
        if ($formation && $formation->brands_ids) {
            // Decode the JSON into an array
            $brandIds = json_decode($formation->brands_ids, true);
    
            // Fetch the brands using the decoded IDs
            return Brand::select('id','name')->whereIn('id', $brandIds)->get();
        }
    
        return collect(); // Return an empty collection if no formation or brand IDs
    }
}
