<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxRbDetailItem extends Model
{
    public $connection = "mysql";
    public $timestamps = false;
    protected $table = 'TRX_RB_DETAIL_ITEMS';

    public function TrxRbHeader()
    {
        return $this->belongsTo('\App\TrxRbHeader','rb_id','rb_id');
    }
}
