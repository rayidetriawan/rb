<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public $connection = "mysql";
    public $timestamps = false;
    protected $dates = ['create_date'];
}
