<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ResellerLogin extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql';
    protected $table = 'reseller_logins';
    protected $guarded = [];
    protected $hidden = ['password'];
}
