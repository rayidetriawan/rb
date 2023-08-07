<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxRbHeader extends Model
{
    public $connection = "mysql";
    public $timestamps = false;
    protected $dates = ['created_date','rb_date','update_date'];
    protected $table = 'TRX_RB_HEADERS';

    public function karyawan(){
        return $this->hasOne('\App\Karyawan','NIK','CREATED_BY');
    }
    public function lastupdateby(){
        return $this->hasOne('\App\Karyawan','NIK','UPDATE_USER');
    }
    public function TrxRbKoreksi(){
        return $this->hasOne('\App\TrxRbKoreksi','RB_ID','RB_ID');
    }

    public function TrxRbDetailDokumen(){
        return $this->hasMany('\App\TrxRbDetailDokumen','RB_ID','RB_ID');
    }

    public function TrxRbDetailItem(){
        return $this->hasMany('\App\TrxRbDetailItem','RB_ID','RB_ID');
    }
    public function TrxRbDetailApprove(){
        return $this->hasMany('\App\TrxRbDetailApproval','RB_ID','RB_ID');
    }
    public function TrxRbDetailSendEmail(){
        return $this->hasMany('\App\TrxRbDetailSendEmail','RB_ID','RB_ID');
    }
    public function cabang(){
        return $this->hasOne('\App\Branch','BRANCH_ID', 'BRANCH_ID');
    }
    public function bankCabang(){
        return $this->hasOne('\App\Bank','bank_name', 'bank');
    }
}
