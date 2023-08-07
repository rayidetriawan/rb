<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationEmailApproval implements ShouldQueue
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
            'rb_id'         => $this->dataemail['RB_ID'],
            'to'            => $this->dataemail['to'],
            'cabang'        => $this->dataemail['cabang'],
            'pemohon'       => $this->dataemail['pemohon'],
            'mengetahui'    => null,
            'j_mengetahui'  => null
        ];

        \Mail::to($this->dataemail['email'])->send(new \App\Mail\NewTicketToApproval($email));
        
    }
}
