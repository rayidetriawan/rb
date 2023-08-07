<?php

namespace App\Jobs;

use App\MstRoutingEmail;
use App\TrxRbDetailApproval;
use App\TrxRbDetailItem;
use App\TrxRbDetailSendEmail;
use App\TrxRbHeader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportRB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $dataemail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dataemail)
    {
        $this->dataemail = $dataemail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        foreach ($this->dataemail as $rbid) {
            // export to pdf
            $header = TrxRbHeader::where('RB_ID', $rbid)->first();
            $createdate = strftime("%A, %d %B %Y", strtotime($header->created_date));
            $mengetahui = TrxRbDetailApproval::where('RB_ID', $rbid)->orderBy('LEVELING', 'asc')->get();
            
            $pdf= Pdf::loadView('rb_formated',compact('createdate', 'header', 'mengetahui'))->setPaper('a4', 'portrait')->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'sans-serif']);
            
            $path = public_path('/pdf/');
            $fileName =  $rbid.'.pdf' ;
            $pdf->save($path . '/' . $fileName);
            $pdf = public_path('/pdf/'.$fileName);
            
            // update di header
            $link = [
                'file_export' => $fileName
            ];
            TrxRbHeader::where('RB_ID', $rbid)->update($link);

        }
        
    }
}
