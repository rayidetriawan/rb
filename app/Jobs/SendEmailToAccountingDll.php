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

class SendEmailToAccountingDll implements ShouldQueue
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
            $header = TrxRbHeader::where('rb_id', $rbid)->first();
            $createdate = strftime("%A, %d %B %Y", strtotime($header->created_date));
            $mengetahui = TrxRbDetailApproval::where('rb_id', $rbid)->orderBy('leveling', 'asc')->get();
            
            $pdf= Pdf::loadView('rb_formated',compact('createdate', 'header', 'mengetahui'))->setPaper('a4', 'portrait')->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'sans-serif']);
            
            $path = public_path('/pdf/');
            $fileName =  $rbid.'.pdf' ;
            $pdf->save($path . '/' . $fileName);
            $pdf = public_path('/pdf/'.$fileName);
            
            // update di header
            $link = [
                'file_export' => $fileName
            ];
            TrxRbHeader::where('rb_id', $rbid)->update($link);

            // send mail
            $sendemail = TrxRbDetailSendEmail::where('rb_id', $rbid)->where('status', 'W')->get();
        }
        if(!empty($sendemail)){
            foreach ($sendemail as $value) {
                # code...
                $email = MstRoutingEmail::where('email', $value->email)->first();
                $header = TrxRbHeader::where('rb_id', $value->rb_id)->first();
                $data = [
                    'to'        => $email->nama,
                    'rb_id'     => $value->rb_id,
                    'pemohon'   => $header->karyawan->nama,
                    'cabang'    => $header->cabang->branch_name,
                    'file'      => $pdf,
                    
                ];
                // \Mail::to('detriawanrayi@gmail.com')->send(new \App\Mail\NewSendEmailToAccountingDll($data));
                \Mail::to($value->email)->send(new \App\Mail\NewSendEmailToAccountingDll($data));
                \Log::info('Send Mail RB CC to '.$value->email);
                $update = [
                    'status' => 'D',
                    'update_date' => date('Y-m-d H:i:s')
                ];
                TrxRbDetailSendEmail::where('rb_id', $value->rb_id)->where('email', $value->email)->update($update);
            }
        }
        
    }
}
