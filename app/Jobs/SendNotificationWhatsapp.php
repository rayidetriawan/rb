<?php

namespace App\Jobs;

use App\MstRoutingEmail;
use App\TrxRbDetailSendWhatsapp;
use App\TrxRbHeader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data  = TrxRbHeader::where('rb_id', $this->data['rb_id'])->first();

        if ($this->data['flag'] == 'NOTIF') {
            # code...
            $tmplt = 'Halo *'.$this->data['to'].'*,
            
Permintaan *RB BARU* '.$this->data['rb_id'].', harap untuk ditindaklanjuti.
Nama Pemohon : '.$this->data['pemohon'].'
Cabang : '.$this->data['cabang'].'

Klik tautan berikut untuk login
http://intranet.hondamitrajaya.com/
            
_Ini adalah pesan yang dibuat secara otomatis, mohon tidak untuk dibalas._';
    
            $data = [
                'rb_id'       => $data->rb_id,
                'cabang'      => $data->branch_id,
                'grup'        => 'Dealer',
                'schema_name' => $data->schema_name,
                'mobile_number' => $this->data['hp'],
                'message'       => $tmplt,
                'flag_status'   => 0,
                'flag_group'    => 0,
                'create_by'     => Auth::user()['username'],
                'create_date'   => date('Y-m-d H:i:s'),
                'flag_send'     => 'NOTIF',
            ];
            
            TrxRbDetailSendWhatsapp::insert($data);

            \Log::info('Send WA notif to '.$this->data['hp']);
        }else if($this->data['flag'] == 'CC'){
            foreach ($this->data['datacc'] as $value) {
                $cc = MstRoutingEmail::where('phone_no', $value['phone_no'])->first();
                $tmplt = 'Halo *'.$cc->nama.'*,
                
Permintaan *RB BARU* '.$this->data['rb_id'].', telah selesai disetujui.

Nama Pemohon : '.$this->data['pemohon'].'
Cabang : '.$this->data['cabang'].'

Unduh file RB dihalaman detail Pengajuan RB.

Klik tautan berikut untuk login.
http://intranet.hondamitrajaya.com/
                
_Ini adalah pesan yang dibuat secara otomatis, mohon tidak untuk dibalas._';
        
                $data = [
                    'rb_id'       => $this->data['rb_id'],
                    'cabang'      => $value['cabang'],
                    'grup'        => $value['grup'],
                    'schema_name' => $value['schemaname'],
                    'mobile_number' => $value['phone_no'],
                    'message'       => $tmplt,
                    'flag_status'   => 2, //pesan ditahan terlebih dahulu
                    'flag_group'    => 0,
                    'create_by'     => Auth::user()['username'],
                    'create_date'   => date('Y-m-d H:i:s'),
                    'flag_send'     => 'CC',
                ];

                TrxRbDetailSendWhatsapp::insert($data);
                \Log::info('Scedule cc wa');
            }
        }else if ($this->data['flag'] == 'TOLAK') {
            # code...
            $tmplt = 'Halo *'.$this->data['pemohon'].'*,
            
Permintaan RB Anda '.$this->data['rb_id'].' *Ditolak* oleh '.$this->data['ditolak'].' ('.$this->data['j_ditolak'].' '.$this->data['j_ditolak'].') !
            
Klik tautan berikut untuk login
http://intranet.hondamitrajaya.com/

_Ini adalah pesan yang dibuat secara otomatis, mohon tidak untuk dibalas._';
    
            $data = [
                'rb_id'       => $data->rb_id,
                'cabang'      => $data->branch_id,
                'grup'        => 'Dealer',
                'schema_name' => $data->schema_name,
                'mobile_number' => $this->data['wato'],
                'message'       => $tmplt,
                'flag_status'   => 0,
                'flag_group'    => 0,
                'create_by'     => Auth::user()['username'],
                'create_date'   => date('Y-m-d H:i:s'),
                'flag_send'     => 'NOTIF',
            ];
            
            TrxRbDetailSendWhatsapp::insert($data);

            \Log::info('Send WA notif tolak to '.$this->data['wato']);
        }else if ($this->data['flag'] == 'OTORISASI') {
            # code...
            $tmplt = 'Halo *'.$this->data['to'].'*,
            
Permintaan *RB BARU* '.$this->data['rb_id'].', telah selesai disetujui.
Harap untuk segera melakukan pencairan dana.
Nama Pemohon : '.$data->karyawan->nama.'
Cabang : '.$data->cabang->branch_name.'
            
_Ini adalah pesan yang dibuat secara otomatis, mohon tidak untuk dibalas._';
    
            $data = [
                'rb_id'       => $data->rb_id,
                'cabang'      => $data->branch_id,
                'grup'        => 'Dealer',
                'schema_name' => $data->schema_name,
                'mobile_number' => $this->data['wato'],
                'message'       => $tmplt,
                'flag_status'   => 0,
                'flag_group'    => 0,
                'create_by'     => Auth::user()['username'],
                'create_date'   => date('Y-m-d H:i:s'),
                'flag_send'     => 'CC',
            ];
            
            TrxRbDetailSendWhatsapp::insert($data);

            \Log::info('Send WA notif to '.$this->data['wato']);
        }else if ($this->data['flag'] == 'REVISI') {
            # code...
            $tmplt = 'Halo *'.$this->data['pemohon'].'*,
            
Permintaan RB Anda '.$this->data['rb_id'].' harus *Dikoreksi*. Koreksi ini diajukan oleh Bapak/Ibu '.$this->data['perevisi'].' ('.$this->data['j_perevisi'].' '.$this->data['j_perevisi'].').

Untuk koreksi pengajuan klik menu *Pengajuan > Koreksi Pengajuan*.

Klik tautan berikut untuk login
http://intranet.hondamitrajaya.com/
            
_Ini adalah pesan yang dibuat secara otomatis, mohon tidak untuk dibalas._';
    
            $data = [
                'rb_id'       => $data->rb_id,
                'cabang'      => $data->branch_id,
                'grup'        => 'Dealer',
                'schema_name' => $data->schema_name,
                'mobile_number' => $this->data['wato'],
                'message'       => $tmplt,
                'flag_status'   => 0,
                'flag_group'    => 0,
                'create_by'     => Auth::user()['username'],
                'create_date'   => date('Y-m-d H:i:s'),
                'flag_send'     => 'NOTIF',
            ];
            
            TrxRbDetailSendWhatsapp::insert($data);

            \Log::info('Send WA revisi tolak to '.$this->data['wato']);
        }else if ($this->data['flag'] == 'SELESAI') {
            # code...
            $tmplt = 'Halo *'.$this->data['pemohon'].'*,
            
Permintaan RB Anda '.$this->data['rb_id'].' telah selesai, dan dana telah ditransfer.

Klik tautan berikut untuk login
http://intranet.hondamitrajaya.com/
            
_Ini adalah pesan yang dibuat secara otomatis, mohon tidak untuk dibalas._';
    
            $data = [
                'rb_id'       => $data->rb_id,
                'cabang'      => $data->branch_id,
                'grup'        => 'Dealer',
                'schema_name' => $data->schema_name,
                'mobile_number' => $this->data['wato'],
                'message'       => $tmplt,
                'flag_status'   => 0,
                'flag_group'    => 0,
                'create_by'     => Auth::user()['username'],
                'create_date'   => date('Y-m-d H:i:s'),
                'flag_send'     => 'NOTIF',
            ];
            
            TrxRbDetailSendWhatsapp::insert($data);

            \Log::info('Send WA notif selesai to '.$this->data['wato']);
        }else if ($this->data['flag'] == 'NKOREKSI') {
            # code...
            $tmplt = 'Halo *'.$this->data['to'].'*,
            
Permintaan RB '.$this->data['rb_id'].' telah dikoreksi, harap untuk ditindaklanjuti kembali.
Nama Pemohon : '.$this->data['pemohon'].'
Cabang : '.$this->data['cabang'].'

Klik tautan berikut untuk login
http://intranet.hondamitrajaya.com/
            
_Ini adalah pesan yang dibuat secara otomatis, mohon tidak untuk dibalas._';
    
            $data = [
                'rb_id'       => $data->rb_id,
                'cabang'      => $data->branch_id,
                'grup'        => 'Dealer',
                'schema_name' => $data->schema_name,
                'mobile_number' => $this->data['hp'],
                'message'       => $tmplt,
                'flag_status'   => 0,
                'flag_group'    => 0,
                'create_by'     => Auth::user()['username'],
                'create_date'   => date('Y-m-d H:i:s'),
                'flag_send'     => 'NOTIF',
            ];
            
            TrxRbDetailSendWhatsapp::insert($data);

            \Log::info('Send WA notif koreksi to '.$this->data['hp']);
        }
    }
}
