<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationEmailToPengaju implements ShouldQueue
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
            'cabang'        => $this->dataemail['cabang'],
            'pemohon'       => $this->dataemail['pemohon']
        ];

        \Mail::to($this->dataemail['emailto'])->send(new \App\Mail\NewTicketToPengaju($email));
        \Log::info('Send Mail pengaju to '.$this->dataemail['emailto']);
    }
}
