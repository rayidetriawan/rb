<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxRbDetailSendEmail extends Model
{
    public $timestamps = false;
    protected $table = 'TRX_RB_DETAIL_SEND_EMAILS';

    public function TrxRbHeader()
    {
        return $this->belongsTo('\App\TrxRbHeader','rb_id','rb_id');
    }
}
