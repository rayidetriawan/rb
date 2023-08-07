<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationEmailKoreksiToCabang implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $dataemail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->dataemail = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = [
            'rb_id'         => $this->dataemail['rb_id'],
            'perevisi'      => $this->dataemail['perevisi'],
            'pemohon'       => $this->dataemail['pemohon'],
            'alasan'        => $this->dataemail['alasan']
        ];
        
        \Mail::to($this->dataemail['emailto'])->send(new \App\Mail\NewTicketToPengajuKoreksi($email));
        \Log::info('Send Mail koreksi cabang to '.$this->dataemail['emailto']);
    }
}
