<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankCabang;
use App\Branch;
use App\Departemen;
use App\Jobs\SendNotificationEmailApproval;
use App\Jobs\SendNotificationEmailKoreksi;
use App\Jobs\SendNotificationEmailToAdminHo;
use App\Jobs\SendNotificationWhatsapp;
use App\MstRoutingEmail;
use App\TrxRbKoreksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Karyawan;
use App\User;
use App\TrxRbHeader;
use App\TrxRbDetailItem;
use App\TrxRbDetailDokumen;
use App\TrxRbDetailApproval;
use App\TrxRbTracking;
use File;
use DataTables;

class PengajuanController extends Controller
{
    public function index()
    {

        $user = Karyawan::where('nik', '=', Auth::user()->username)->first();
        $kodeRb = $this->getKodeRB($user);
        $bank = Bank::get();
        
        $tanggal = date('d F Y');
        
        return view('transaksi.pengajuan', compact('user','bank','kodeRb','tanggal'));
    }

    public function list(Request $request)
    {
        $cabang = Branch::orderby('branch_name', 'asc')->get();
        $user = Karyawan::where('nik', '=', Auth::user()->username)->first();
        
        $data = TrxRbHeader::where('CREATED_BY', $user->nik)
            ->where('BRANCH_ID', $user->branch_id)
            ->whereNotIn('status', [7])
            ->orderBy('created_date', 'DESC')
            ->paginate(10);

        return view('transaksi.pengajuan_riwayat', compact('data','cabang'));
    }
    public function liveSearch(Request $request)
    {
        
        $search = $request->get('search');
        $user = Karyawan::where('nik', '=', Auth::user()->username)->first();
        if ($request->ajax()) {

            $data = TrxRbHeader::select('TRX_RB_HEADERS.RB_ID','TRX_RB_HEADERS.status','TRX_RB_HEADERS.total_harga','TRX_RB_HEADERS.update_user','TRX_RB_HEADERS.update_date', 'TRX_RB_HEADERS.CREATED_BY', 'TRX_RB_HEADERS.branch_id','TRX_RB_HEADERS.created_date')->join('TRX_RB_DETAIL_ITEMS', 'TRX_RB_DETAIL_ITEMS.RB_ID', '=', 'TRX_RB_HEADERS.RB_ID');
            
            $data->where('TRX_RB_HEADERS.CREATED_BY', Auth::user()->username)->where('TRX_RB_HEADERS.branch_id', $user->branch_id);

            if (!empty($request->get('status'))) {
                if($request->get('status') == 'O'){
                    $data->where('TRX_RB_HEADERS.status', '0');
                }else{
                    $data->where('TRX_RB_HEADERS.status', $request->get('status'));
                }
            }
            
            if (!empty($request->get('fromdate')) || !empty($request->get('todate'))) {
                $data->whereBetween('TRX_RB_HEADERS.created_date', [date('Y-m-d', strtotime($request->get('fromdate'))), date('Y-m-d', strtotime($request->get('todate').'+ 1 day')) ]);
            }
            if (!empty($request->get('search'))) {
                $data->where('TRX_RB_HEADERS.RB_ID', 'LIKE', "%$search%")->where('TRX_RB_HEADERS.CREATED_BY', Auth::user()->username)->where('TRX_RB_HEADERS.branch_id', $user->branch_id);
                $data->orWhere('TRX_RB_HEADERS.total_harga', 'LIKE', "%$search%")->where('TRX_RB_HEADERS.CREATED_BY', Auth::user()->username)->where('TRX_RB_HEADERS.branch_id', $user->branch_id);
                $search1 = strtoupper($search);
                $data->orWhere(DB::raw('UPPER(TRX_RB_DETAIL_ITEMS.item)'), 'like', "%$search1%")->where('TRX_RB_HEADERS.CREATED_BY', Auth::user()->username)->where('TRX_RB_HEADERS.branch_id', $user->branch_id);
            }

            $data->groupBy('TRX_RB_HEADERS.RB_ID','TRX_RB_HEADERS.status','TRX_RB_HEADERS.total_harga','TRX_RB_HEADERS.update_user','TRX_RB_HEADERS.update_date','TRX_RB_HEADERS.CREATED_BY', 'TRX_RB_HEADERS.branch_id','TRX_RB_HEADERS.created_date');

            $datafix = $data->orderBy('TRX_RB_HEADERS.update_date', 'DESC')->get();
            
            $output="";
            foreach ($datafix as $no => $hist) {
                
                $no = $no + 1 ;
                if($hist->status == 1){
                    $status = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                            Menunggu Aproval Kacab/ Kabeng
                                        </span>';
                }else if($hist->status == 2){
                    $status = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                        Proses Validasi Pengesahan
                                    </span>';
                }else if($hist->status == 3){
                    $status = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                        Menunggu Aproval Terkait
                                    </span>';
                }else if($hist->status == 4){
                    $status = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                        Menunggu Approval Management Terkait
                                    </span>';
                }else if($hist->status == 5){
                    $status = '<span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                        Ditolak
                                    </span>';
                }else if($hist->status == 6){
                    $status = '<span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                        Menunggu otorisasi
                                    </span>';
                }else if($hist->status == 7){
                    $status = '<span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                        RB Harus Dikoreksi
                                    </span>';
                }else if($hist->status == 0){
                    $status = '<span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                        RB telah selesai 
                                    </span>';
                }
                $itemfix="";
                foreach($hist->TrxRbDetailItem as $row1 => $item){
                    $itemfix .='<li>'.$item->ITEM.'<div class="text-muted">'.$item->QTY.' x Rp.&nbsp;'.$item->HARGA.' = Rp.'.$item->QTY*$item->HARGA.'</div></li>';
                };
                
                $output .='<tr>'.
                        '<td class="align-top">'.$no.'</td>'.
                        '<td class="align-top">'.$hist->RB_ID.'</td>'.
                        '<td class="align-top"><ul class="ps-2 vstack mb-1">'.$itemfix.'</ul><br>Total = Rp.&nbsp;'.$hist->total_harga.'</td>'.
                        '<td class="align-top">'.$status.'</td>'.
                        '<td class="align-top">'.$hist->lastupdateby['NAMA'].'</td>'.
                        '<td class="align-top">'.date('d/m/Y H:i', strtotime($hist->created_date)).'</td>'.
                        '<td class="align-top text-center"><a href="'.route("pengajuan.detail", $hist->RB_ID).'" class="btn btn-sm btn-light">Detail</a></td>'.
                    '</tr>';

            }

            return Response($output);
        }
    }
    public function _leveling($RB_ID)
    {
        // $q = TrxRbDetailApproval::select(DB::raw('max(TRX_RB_DETAIL_APPROVAL.LEVELING) as leveling'))
        //     ->where('RB_ID',$RB_ID)
        //     ->first();
        $q2 = 0;
        $q2 = TrxRbDetailApproval::where('RB_ID', $RB_ID)->max('leveling');
        $kd = (int)$q2 + 1;
        
        return $kd;
    }
    public function _send_mail($RB_ID)
    {
        $q  = TrxRbDetailApproval::select(DB::raw('min(TRX_RB_DETAIL_APPROVAL.LEVELING) as leveling'))
                ->where('RB_ID',$RB_ID)
                ->where('status',0)
                ->first();
    
             // get user by level and RB_ID
            $q2 = TrxRbDetailApproval::where('leveling', $q->leveling)->where('RB_ID',$RB_ID)->first();
          
            $data = TrxRbHeader::where('RB_ID', $RB_ID)->first();
            $user = Karyawan::where('nik', '=', $q2->approve_by)->first();
            $email = [
                'RB_ID'         => $RB_ID,
                'to'            => $user->nama,
                'email'         => $user->user->email,
                'cabang'        => $data->cabang->branch_name,
                'pemohon'       => $data->karyawan->nama,
                'mengetahui'    => null,
                'j_mengetahui'  => null
            ];

        $cek = TrxRbHeader::where('koreksi', 'D')->where('RB_ID', $RB_ID)->first();
        if (!empty($cek)) {
            $job = new SendNotificationEmailKoreksi($email);
        }else{    
            $job = new SendNotificationEmailApproval($email);
        }
        $this->dispatch($job);
        return true;
    }
    public function simpan(Request $request)
    {
        $bank = Bank::where('id', $request->bank)->first();
        $user  = Karyawan::where("nik", $request->nama)->first();
        $kodeRb = $this->getKodeRB($user);
        $totalharga = str_replace('.', '', $request->total);
            
        // send notification email
        $email = [
            'to'            => 'Admin RB',
            'RB_ID'         => $kodeRb,
            'cabang'        => $user->cabang->branch_name,
            'pemohon'       => $user->nama,
            'mengetahui'    => null,
            'j_mengetahui'  => null
        ];
        
        $job = new SendNotificationEmailToAdminHo($email);
        $this->dispatch($job);
            
        $header = [
            "RB_ID"        => $kodeRb,
            "CREATED_BY"   => $request->nama,
            "KET_CKEDITOR"   => $request->note,
            "STATUS"       => 2,
            "NAMA_REK"     => $bank->BANK_ACCOUNT_NAME,
            "BANK"         => $bank->BANK_NAME,
            "NO_REK"       => $bank->BANK_ACCOUNT_NO,
            "TOTAL_HARGA"  => str_replace(',', '.', $totalharga),
            "BRANCH_ID"    => $request->cabang,
            "RB_DATE"      => date('Y-m-d'),
            "CREATED_DATE" => date('Y-m-d H:i:s'),
            "CREATE_USER"  => $request->nama,
        ];
        
        TrxRbHeader::insert($header);

        $approval2 = [
            'rb_id'         => $kodeRb,
            'approve_by'    => Auth::user()->username,
            'status'        => 1,
            'leveling'      => 0,
            'flag'          => 'MEMBUAT',
            'approve_date'  => date('Y-m-d H:i:s'),
        ];
        TrxRbDetailApproval::insert($approval2);

        if ($request->file('dokumen') != null) {
            # code...
            foreach($request->file('dokumen') as $file)
            {
    
                $nama_ft = $kodeRb. '-' .$file->getClientOriginalName();
                $file->move('dokumen', $nama_ft);
    
                $dok[] = [
                    "RB_ID"         => $kodeRb,
                    "DOKUMEN_NAME"  => $nama_ft
                ];
            }
            TrxRbDetailDokumen::insert($dok);
        }

        foreach($request->addmore as $data){
            $harga = str_replace('.', '', $data['harga']);
            $detail = [
                'RB_ID'     => $kodeRb,
                'ITEM'      => $data['keterangan'],
                'QTY'       => (int)$data['qty'],
                'HARGA'     => str_replace(',', '.', $harga),
                'ITEM_ID'   => $this->itemId(),
            ];
            TrxRbDetailItem::insert($detail);
        }
        // dd($detail);
        $tracking = [
            'RB_ID'         => $kodeRb,
            'status'        => 'Create RB',
            'id_user'       => $request->nama,
            'created_date'  => date('Y-m-d H:i:s')
        ];

        TrxRbTracking::insert($tracking);
        
        return redirect()->route('pengajuan.list')->with('message','RB Berhasil Disimpan!');
    }

    public function getcabang(Request $request)
    {
        # mapping email
        // $data = MstRoutingEmail::with('cabang')->where('schema_name', $request->schema_name)->where('FLAG', $request->grup)->get();
        $data = Branch::where('schema_name', $request->schema_name)->get();

        return response()->json($data);
    }
    public function downloadrb($rbid)
    {
        $header = TrxRbHeader::where('RB_ID', $rbid)->first();
        //cek apakah sudah ada file nya atau belum
        
        if(empty($header->file_export)){

            $createdate = strftime("%A, %d %B %Y", strtotime($header->created_date));
            $mengetahui = TrxRbDetailApproval::where('RB_ID', $rbid)->orderBy('leveling', 'asc')->get();
            
            $pdf= Pdf::loadView('rb_formated',compact('createdate', 'header', 'mengetahui'))->setPaper('a4', 'portrait')->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'sans-serif']);
            
            $path = public_path('/pdf');
            $fileName =  $rbid.'.'. 'pdf' ;
            $pdf->save($path . '/' . $fileName);
            
            // update di header
            $link = [
                'file_export' => $fileName
            ];
            TrxRbHeader::where('RB_ID', $rbid)->update($link);

            return $pdf->download($rbid.'.pdf');
        }else{
            $fileName =  $rbid.'.'. 'pdf' ;
            $file_path = public_path('pdf/' . $fileName);
            return response()->download($file_path, $fileName);
        }
    }
    public function detail($id)
    {
        $data     = TrxRbHeader::where('RB_ID', $id)->first();
        $cek      = TrxRbDetailApproval::where('RB_ID', $id)
                    ->where('approve_by', Auth::user()->username)
                    ->where('status',0)
                    ->first();
        
        // cek untuk otorisasi
        $adminfinance = Departemen::where('id', Auth::user()->karyawan->id_bag_dept)->first();
        if($adminfinance->id == 10){
            $cekfinance = true;
        }else{
            $cekfinance = false;
        }

        $tracking = TrxRbTracking::where('RB_ID', $id)->orderBy('created_date', 'asc')->get();
        $user     = Karyawan::where('nik', '=', Auth::user()->username)->first();
        $mengetahui = TrxRbDetailApproval::where('RB_ID', $id)
                        ->where('FLAG','MENGETAHUI')
                        ->orderBy('leveling', 'asc')
                        ->get();
        // dd($mengetahui);
        $menyetujui = TrxRbDetailApproval::where('RB_ID', $id)
                        ->where('FLAG','MENYETUJUI')
                        ->orderBy('leveling', 'asc')
                        ->get();
        
        $diketahui = Karyawan::whereIn('id_jabatan',[2,3,7,8,11])->get(); //manager dan branch head
        $disetujui = Karyawan::whereIn('id_jabatan',[2,3,7,8,11])->get(); //manager, gm, direktur, owner
// dd($mengetahui,$menyetujui);
        return view('transaksi.pengajuanDetail', compact('data','cek','cekfinance','tracking','user','mengetahui','menyetujui','diketahui','disetujui'));
    }
    public function itemId()
    {
        $q = TrxRbDetailItem::select(DB::raw('max(SUBSTR(TRX_RB_DETAIL_ITEMS.ITEM_ID,7)) as ITEM_ID'))
            ->get();
        $year = substr(date('Y'), -2);
        if (count($q) != 0) {
            foreach ($q as $k) {
                $tmp = ((int)$k->item_id) + 1;
                $a = strlen($tmp);

                for ($i = $a; $i < 6; $i++) {
                    # code...
                    $no_baru = "0" . (string) $tmp;
                    $a = strlen($no_baru);
                }
                
                $kd = 'ITM' . "-" . $year . "-". sprintf("%07s", $no_baru);  
            }
        }
        else {
            $kd = 'ITM' . "-" . $year . "-". "-0000001";
        }

        return $kd;
    }
    public function getKodeRB($user)
    {
        $branch = $user->branch_id;
        $year = substr(date('Y'), -2);
        $kd = null;

        $q = TrxRbHeader::select(DB::raw('max(SUBSTR(TRX_RB_HEADERS.RB_ID,17)) as RB_ID'))
            ->where('branch_id',$branch)
            ->get();
            
        if (count($q) != 0) {
            foreach ($q as $k) {
                $tmp = ((int)$k->RB_ID) + 1;
                $a = strlen($tmp);
                
                for ($i=$a; $i<6 ; $i++) { 
                    # code...
                    $no_baru = "0" . (string)$tmp;
                    $a = strlen($no_baru);
                    
                }
                $kd = 'TUA' . "-" . $year . "-". "AQUA" . "-" . $branch . "-" . sprintf("%06s", $no_baru);
            }
        }
        else {
            $kd = 'TUA' . "-" . $year . "-". "AQUA" ."-" . $branch . "-000001";
        }
        
        return $kd;

    }
}
