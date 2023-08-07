<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $connection = "mysql";
    public $timestamps = false;
    protected $table = 'branches';

    public function karyawan(){
        return $this->belongsTo('\App\Karyawan','branch_id', 'branch_id');
    }

    public function trxrbheader(){
        return $this->belongsTo('\App\TrxRbHeader','branch_id', 'branch_id');
    }
}
