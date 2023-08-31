<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationEmailToAdminHo implements ShouldQueue
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
            'to'            => 'Admin',
            'rb_id'         => $this->dataemail['RB_ID'],
            'cabang'        => $this->dataemail['cabang'],
            'pemohon'       => $this->dataemail['pemohon'],
            'mengetahui'    => $this->dataemail['mengetahui'],
            'j_mengetahui'  => $this->dataemail['j_mengetahui']
        ];

        \Mail::to('faizalcikal2121@gmail.com@gmail.com')->send(new \App\Mail\NewTicketToAdminHo($email));
    }
}
