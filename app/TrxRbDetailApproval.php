<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxRbDetailApproval extends Model
{
    public $connection = "mysql";
    public $timestamps = false;
    protected $dates = ['APPROVE_DATE'];
    protected $table = 'TRX_RB_DETAIL_APPROVALS';

    public function karyawan(){
        return $this->hasOne('\App\Karyawan','nik','APPROVE_BY');
    }
    public function trxheader(){
        return $this->hasOne('\App\TrxRbHeader','RB_ID','RB_ID');
    }
}
