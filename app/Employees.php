<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    //
        protected $table = 'employees';
        protected $guarded = [];
        protected $connection = 'mysql2'; // Specify the connection if different from default

}
