<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxRbTracking extends Model
{
    public $connection = "mysql";
    public $timestamps = false;
    protected $table = 'TRX_RB_TRACKINGS';

    protected $dates = ['created_date'];

    public function karyawan(){
        return $this->hasOne('\App\Karyawan','NIK','ID_USER');
    }
    
}
