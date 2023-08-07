<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Jobs\ExportRB;
use App\Jobs\SendEmailToAccountingDll;
use App\Jobs\SendNotificationEmailApproval;
use App\Jobs\SendNotificationEmailKoreksi;
use App\Jobs\SendNotificationEmailKoreksiToCabang;
use App\Jobs\SendNotificationEmailToAdminHo;
use App\Jobs\SendNotificationEmailToKudus;
use App\Jobs\SendNotificationEmailTolak;
use App\Jobs\SendNotificationEmailToPengaju;
use App\Jobs\SendNotificationWhatsapp;
use App\TrxRbDetailSendEmail;
use App\TrxRbDetailSendWhatsapp;
use App\TrxRbKoreksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\TrxRbHeader;
use App\TrxRbTracking;
use App\TrxRbDetailApproval;
use App\Karyawan;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    public function index()
    {
        $user = Karyawan::where('nik', Auth::user()->username)->first();
        $RB_ID = [];
        $cek3 = null;
        $STATUS = null;
        $cek = TrxRbDetailApproval::where('APPROVE_BY', Auth::user()->username)
                ->where('STATUS',0)
                ->get();
              
        // cek LEVELING
        foreach ($cek as  $value) {
            $RB_ID2 = $value->RB_ID;
            $level = $value->LEVELING - 1;
            $cek2 = TrxRbDetailApproval::where('RB_ID', $RB_ID2)
                    ->where('LEVELING', $level)
                    ->first();
                    
            if($cek2 != null){
                $STATUS = $cek2->STATUS;       
               
                if($STATUS != '0'){
                    $cek3[] = $cek2->RB_ID; 
                }
            }
        }
        if($cek3 != null){
            foreach ($cek3 as $key ) {
                # code...
                $RB_ID[] = $key;
            }
        }

        $data = TrxRbHeader::whereIn('RB_ID', $RB_ID)
            ->orderBy('CREATED_DATE', 'ASC')
            ->paginate(10);

        return view('transaksi.persetujuan', compact('data','user'));
    }

    public function riwayat()
    {
        $user = Karyawan::where('nik', '=', Auth::user()->username)->first();
        $data = null;
        $cek = TrxRbDetailApproval::where('APPROVE_BY', Auth::user()->username)->whereIn('STATUS',[1,2,3])->get();
        
        if(count($cek) != 0){
            
            foreach ($cek as $cek1) {
                $RB_ID[] = $cek1->RB_ID;
            }

            $data = TrxRbHeader::whereIn('RB_ID', $RB_ID)
                ->orderBy('CREATED_DATE', 'DESC')
                ->paginate(10);
        }
        $cabang = Branch::orderby('branch_name', 'asc')->get();

        return view('transaksi.persetujuan_riwayat', compact('data','user','cabang'));
    }
    public function liveSearch(Request $request)
    {
        $output="";
        $search = $request->get('search');
        $user = Karyawan::where('nik', '=', Auth::user()->username)->first();
        if ($request->ajax()) {
            $data = TrxRbHeader::select(
                'TRX_RB_HEADER.RB_ID',
                'TRX_RB_DETAIL_ITEM.item',
                'TRX_RB_DETAIL_ITEM.qty',
                'TRX_RB_DETAIL_ITEM.harga',
                'TRX_RB_HEADER.STATUS',
                'TRX_RB_HEADER.total_harga',
                'TRX_RB_HEADER.UPDATE_USER',
                'TRX_RB_HEADER.CREATED_BY',
                'TRX_RB_HEADER.CREATED_DATE')
            ->join('TRX_RB_DETAIL_ITEM', 'TRX_RB_HEADER.RB_ID', '=', 'TRX_RB_DETAIL_ITEM.RB_ID')
            ->join('TRX_RB_DETAIL_APPROVALS', 'TRX_RB_HEADER.RB_ID', '=', 'TRX_RB_DETAIL_APPROVALS.RB_ID');
                
            if (!empty($request->get('STATUS'))) {
                if($request->get('STATUS') == 'O'){
                    $data->where('TRX_RB_HEADER.STATUS', '0');
                }else{
                    $data->where('TRX_RB_HEADER.STATUS', $request->get('STATUS'));
                }
            }

            if (!empty($request->get('cabang'))) {
                $data->where('TRX_RB_HEADER.branch_id', substr($request->get('cabang'), 0, 3));
            }
            
            if (!empty($request->get('fromdate')) || !empty($request->get('todate'))) {
                $data->whereBetween('TRX_RB_HEADER.CREATED_DATE', [date('Y-m-d', strtotime($request->get('fromdate'))), date('Y-m-d', strtotime($request->get('todate').'+ 1 day')) ]);
            }
            if (!empty($request->get('search'))) {
                $data->where('TRX_RB_HEADER.RB_ID', 'like', '%'.$search.'%');
                $data->orWhere(DB::raw('UPPER(TRX_RB_DETAIL_ITEM.item)'), 'like', '%' . strtoupper($search) . '%');
            }
            $data->where('TRX_RB_DETAIL_APPROVALS.APPROVE_BY', Auth::user()->username);
            $data->groupby(
                'TRX_RB_HEADER.RB_ID',
                'TRX_RB_DETAIL_ITEM.item',
                'TRX_RB_DETAIL_ITEM.qty',
                'TRX_RB_DETAIL_ITEM.harga',
                'TRX_RB_HEADER.STATUS',
                'TRX_RB_HEADER.total_harga',
                'TRX_RB_HEADER.UPDATE_USER',
                'TRX_RB_HEADER.CREATED_BY',
                'TRX_RB_HEADER.CREATED_DATE'
            );
            $datafix = $data->orderBy('TRX_RB_HEADER.CREATED_DATE', 'DESC')->get();

            foreach ($datafix as $no => $hist) {
                $itemfix="";
                foreach($hist->TrxRbDetailItem as $item){
                    $itemfix .='<li>'.$item->item.'<div class="text-muted">'.$item->qty.' x Rp.&nbsp;'.$item->harga.' = Rp.'.$item->qty*$item->harga.'</div></li>';
                };

                $no = $no + 1 ;
                if($hist->STATUS == 1){
                    $STATUS = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                            Menunggu Aproval Kacab/ Kabeng
                                        </span>';
                }else if($hist->STATUS == 2){
                    $STATUS = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                        Proses Validasi Pengesahan
                                    </span>';
                }else if($hist->STATUS == 3){
                    $STATUS = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                        Menunggu Aproval Terkait
                                    </span>';
                }else if($hist->STATUS == 4){
                    $STATUS = '<span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                        Menunggu Approval Management Terkait
                                    </span>';
                }else if($hist->STATUS == 5){
                    $STATUS = '<span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                        Ditolak
                                    </span>';
                }else if($hist->STATUS == 6){
                    $STATUS = '<span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                        Menunggu otorisasi
                                    </span>';
                }else if($hist->STATUS == 7){
                    $STATUS = '<span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                        RB Harus Dikoreksi
                                    </span>';
                }else if($hist->STATUS == 0){
                    $STATUS = '<span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                        RB telah selesai 
                                    </span>';
                }
                $output.='<tr>'.
                        '<td class="align-top">'.$no.'</td>'.
                        '<td class="align-top">'.
                            '<a href="'.route("pengajuan.detail", $hist->RB_ID).'">'.$hist->RB_ID.' <i class="ri-link"></i></a>'.'<br><div class="text-muted">Branch : '. $hist->cabang['branch_name'].'</div></td>'.
                        '<td class="align-top"><ul class="ps-2 vstack mb-1">'.$itemfix.'</ul><br>Total = Rp.&nbsp;'.$hist->total_harga.'</td>'.
                        '<td class="align-top">'.$hist->karyawan['nama'].'</td>'.
                        '<td class="align-top">'.$hist->lastupdateby['nama'].'</td>'.
                        '<td class="align-top">'.$STATUS.'</td>'.
                        '<td class="align-top">'.date('d/m/Y H:i', strtotime($hist->CREATED_DATE)).'</td>'.
                        '<td class="align-top"><a href="'.route("pengajuan.detail", $hist->RB_ID).'" class="btn btn-sm btn-light">Detail</a></td>'.
                    '</tr>';
            }

            return Response($output);
        }
    }
    public function _LEVELING($RB_ID)
    {
        // $q = TrxRbDetailApproval::select(DB::raw('max(TRX_RB_DETAIL_APPROVALS.LEVELING) as LEVELING'))
        //     ->where('RB_ID',$RB_ID)
        //     ->first();
        $q2 = TrxRbDetailApproval::where('RB_ID', $RB_ID)->max('LEVELING');
        $kd = (int)$q2 + 1;
        
        return $kd;
    }
    public function _send_mail($RB_ID)
    {
        $q  = TrxRbDetailApproval::select(DB::raw('min(TRX_RB_DETAIL_APPROVALS.LEVELING) as LEVELING'))
                ->where('RB_ID',$RB_ID)
                ->where('STATUS',0)
                ->first();
    
             // get user by level and RB_ID
            $q2 = TrxRbDetailApproval::where('LEVELING', $q->LEVELING)->where('RB_ID',$RB_ID)->first();
          
            $data = TrxRbHeader::where('RB_ID', $RB_ID)->first();
            $user = Karyawan::where('nik', '=', $q2->APPROVE_BY)->first();
            
            $email = [
                'RB_ID'         => $RB_ID,
                'to'            => $user->nama,
                'email'         => $user->user->email,
                'cabang'        => $data->cabang->branch_name,
                'pemohon'       => $data->karyawan->nama,
                'mengetahui'    => null,
                'j_mengetahui'  => null
            ];

        $cek = TrxRbHeader::where('KOREKSI', 'D')->where('RB_ID', $RB_ID)->first();
        if (!empty($cek)) {
            $job = new SendNotificationEmailKoreksi($email);
        }else{    
            $job = new SendNotificationEmailApproval($email);
        }
        $this->dispatch($job);
        return true;
    }

    public function updateSTATUS(Request $request)
    {
        
        // cek apakah user sudah pernah approval sebelumnya atau belum
        $cekApproval = TrxRbDetailApproval::where('RB_ID', '=', $request->RB_ID)
                    ->where('APPROVE_BY', '=', $request->ID_USER)
                    ->where('STATUS', '=', '1')
                    ->first();
        
        // jika belum redireck ke halaman persetujuan
        // 35 adalah STATUS batal oleh admin ho
        
        if($request->STATUS != '3' AND $request->STATUS != '35' AND $cekApproval != null){
            return redirect()->route('persetujuan')->with('messageWarning','RB '.$request->RB_ID.' talah anda proses!');
        }

        // cek apakah "mengetahui" sudah mengkoreksi sebelumnya atau belum
        $cekApproval2 = TrxRbDetailApproval::where('RB_ID', '=', $request->RB_ID)
                    ->where('APPROVE_BY', '=', $request->ID_USER)
                    ->where('STATUS', '=', '2')
                    ->first();
        // jika belum redirect ke halaman persetujuan
        if($cekApproval2 != null){
            return redirect()->route('persetujuan')->with('messageWarning','RB '.$request->RB_ID.' sedang dalam koreksi data!');
        }

        $cekuser= Karyawan::where('nik', '=', $request->id_user)->first();
        $rb     = TrxRbHeader::where('RB_ID', '=', $request->RB_ID)->first();
    
        // update STATUS approval kacab atau kepala bengkel atau manager terkait
        if ($request->STATUS == 2) {
            //cek apakah ada 2 atau lebih mengetahui
            $cek = TrxRbDetailApproval::where('RB_ID', $request->RB_ID)->where('STATUS','0')->count();
            
            // cek STATUS
            if ($cek == 1) {
                # Jika tinggal 1 approval
                $data = [
                    'STATUS'        => $request->STATUS,
                    'UPDATE_USER'   => $cekuser->nik,
                    'UPDATE_DATE'   => date('Y-m-d H:i:s')
                ];

                $email = [
                    'to'            => 'Admin',
                    'RB_ID'         => $request->RB_ID,
                    'cabang'        => $cekuser->cabang->branch_name,
                    'pemohon'       => $rb->karyawan->nama,
                    'mengetahui'    => $cekuser->nama,
                    'j_mengetahui'  => $cekuser->jabatan->nama_jabatan
                ];
                
                TrxRbHeader::where('RB_ID', $request->RB_ID)->update($data);
                $approval = [
                    'STATUS'        => 1,
                    'APPROVE_DATE'  => date('Y-m-d H:i:s'),
                ];
                TrxRbDetailApproval::where('RB_ID', $request->RB_ID)
                ->where('APPROVE_BY', Auth::user()->username)
                ->update($approval);
                
                // email ke admin ho
                $job = new SendNotificationEmailToAdminHo($email);
                $this->dispatch($job);

                // kirim wa notif
                // $this->_send_wanotif($request->RB_ID, 'ADMIN');
            }else{
                // Jika lebih dari 1 approval
                $data = [
                    'UPDATE_USER'   => $cekuser->nik,
                    'UPDATE_DATE'   => date('Y-m-d H:i:s')
                ];

                $approval = [
                    'STATUS'        => 1,
                    'APPROVE_DATE'  => date('Y-m-d H:i:s'),
                ];
                TrxRbHeader::where('RB_ID', $request->RB_ID)->update($data);
                TrxRbDetailApproval::where('RB_ID', $request->RB_ID)
                                ->where('APPROVE_BY', Auth::user()->username)
                                ->update($approval);

                $this->_send_mail($request->RB_ID);
                // $this->_send_wanotif($request->RB_ID, 'NOTIF');
            }
            
            $tracking = [
                'RB_ID'         => $request->RB_ID,
                'STATUS'        => 'RB telah diketahui '. $cekuser->jabatan->nama_jabatan,
                'DESKRIPSI'     => (!empty($request->alasansetuju)) ? $request->alasansetuju : '-',
                'ID_USER'       => $cekuser->nik,
                'CREATED_DATE'  => date('Y-m-d H:i:s')
            ];
            
            TrxRbTracking::insert($tracking);
                        
            return redirect()->route('persetujuan')->with('message','RB Berhasil Diupdate!');
        }
        
        //update pembagian approval admin finance
        if ($request->STATUS == 3){
            if ($request->disetujui == '') {
                return redirect()->back()->withErrors(['msg' => 'Tidak Boleh Kosong']);
            }
            
            $data = TrxRbHeader::where('RB_ID', $request->RB_ID)->first();   
            $RB_ID = $request->RB_ID;
           
            if($request->diketahui != ''){

                foreach($request->diketahui as $result){
                    $level2 = $this->_LEVELING($request->RB_ID);
                    $diketahui = [
                        'RB_ID'         => $RB_ID,
                        'APPROVE_BY'    => $result,
                        'STATUS'        => 0,
                        'FLAG'          => 'MENGETAHUI',
                        'LEVELING'      => $level2,
                        'APPROVE_DATE'  => date('Y-m-d H:i:s'),
                    ];
    
                    TrxRbDetailApproval::insert($diketahui);
                }
            }
            
            foreach($request->disetujui as $setuju){
                $level = $this->_LEVELING($RB_ID) ;
                $disetujui = [
                    'RB_ID'         => $RB_ID,
                    'APPROVE_BY'    => $setuju,
                    'STATUS'        => 0,
                    'LEVELING'      => $level,
                    'FLAG'          => 'MENYETUJUI',
                    'APPROVE_DATE'  => date('Y-m-d H:i:s'),
                ];

                TrxRbDetailApproval::insert($disetujui);
            }

            $data = [
                'STATUS'        => '4',
                'UPDATE_USER'   => $cekuser->nik,
                'UPDATE_DATE'   => date('Y-m-d H:i:s')
            ];

            $tracking = [
                'RB_ID'         => $RB_ID,
                'STATUS'        => 'Menunggu approval Management',
                'DESKRIPSI'     => 'Admin RB telah melakukan penugasan untuk segera di approve',
                'ID_USER'       => $cekuser->nik,
                'CREATED_DATE'  => date('Y-m-d H:i:s')
            ];

            
            TrxRbHeader::where('RB_ID', $RB_ID)->update($data);
            TrxRbTracking::insert($tracking);
            //kirim email 
            $this->_send_mail($RB_ID);

            return redirect()->route('pengesahan')->with('message','RB Berhasil Diupdate!');
        }
        //rb ditolak oleh admin finance
        if ($request->STATUS == 35) {
           
            
            // update STATUS di header
            $data = [
                'STATUS'        => 5,
                'UPDATE_USER'   => $cekuser->nik,
                'UPDATE_DATE'   => date('Y-m-d H:i:s')
            ];
            
            // update tracking
            $tracking = [
                [
                    'RB_ID'         => $request->RB_ID,
                    'STATUS'        => 'Pengajuan ditolak oleh '. $cekuser->jabatan->nama_jabatan .' '.$cekuser->departemen->nama_dept .'!',
                    'DESKRIPSI'     => $request->alasantolak,
                    'ID_USER'       => $cekuser->nik,
                    'CREATED_DATE'  => date('Y-m-d H:i:s')
                ],[
                    'RB_ID'         => $request->RB_ID,
                    'STATUS'        => 'RB closed !',
                    'DESKRIPSI'     => 'Silahkan untuk mengajukan RB baru!',
                    'ID_USER'       => $cekuser->nik,
                    'CREATED_DATE'  => date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes(1)))
                ]
            ];
            
            // update approval
            $approval = [
                'STATUS'        => 2, //STATUS batal
                'APPROVE_DATE'  => date('Y-m-d H:i:s'),
            ];

            $approval2 = [
                'STATUS'        => 3, //STATUS batal (untuk user yg tidak membatalkan tidak bisa diproses)
                'APPROVE_DATE'  => date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes(1))),
            ];

            
            TrxRbHeader::where('RB_ID', $request->RB_ID)->update($data);
            TrxRbTracking::insert($tracking);
            TrxRbDetailApproval::where('RB_ID', $request->RB_ID)
                ->where('APPROVE_BY', $cekuser->nik)
                ->update($approval);
            TrxRbDetailApproval::where('RB_ID', $request->RB_ID)
                ->whereNotIn('STATUS', [1])
                ->whereNotIn('APPROVE_BY', [$cekuser->nik])
                ->update($approval2);

            //kirim notif
            $email = [
                'RB_ID'         => $request->RB_ID,
                'emailto'       => $rb->karyawan->user->email,
                'cabang'        => $rb->cabang->branch_name,
                'pemohon'       => $rb->karyawan->nama,
                'ditolak'       => $cekuser->nama,
                'j_ditolak'     => $cekuser->jabatan->nama_jabatan,
                'd_ditolak'     => $cekuser->departemen->nama_dept,
                'FLAG'          => 'TOLAK'
            ]; 
            // $job = new SendNotificationWhatsapp($watolak);
            // $this->dispatch($job);

            $job = new SendNotificationEmailTolak($email);
            $this->dispatch($job);
            
            return redirect()->route('pengesahan')->with('message','RB Berhasil Dibatalkan!');
        }

        //approval terkait
        if ($request->STATUS == 4) {
            
            $data = [
                'STATUS'        => $request->STATUS,
                'UPDATE_USER'   => $cekuser->nik,
                'UPDATE_DATE'   => date('Y-m-d H:i:s')
            ];
            $ceks = TrxRbDetailApproval::where('RB_ID', $request->RB_ID)->where('APPROVE_BY', $cekuser->nik)->first();
            
            if ($cekuser->jabatan->id == 3 || $cekuser->jabatan->id == 8 || $cekuser->jabatan->id == 11) { //kacab, direktur, owner
                $tracking = [
                    'RB_ID'         => $request->RB_ID,
                    'STATUS'        => ($ceks->FLAG == 'MENGETAHUI') ? 'RB telah diketahui '.$cekuser->jabatan->nama_jabatan : 'RB telah disetujui '.$cekuser->jabatan->nama_jabatan ,
                    'DESKRIPSI'     => (!empty($request->alasansetuju)) ? $request->alasansetuju : '-',
                    'ID_USER'       => $cekuser->nik,
                    'CREATED_DATE'  => date('Y-m-d H:i:s')
                ];
            }else{

                $tracking = [
                    'RB_ID'         => $request->RB_ID,
                    'STATUS'        => ($ceks->FLAG == 'MENGETAHUI') ? 'RB telah diketahui '.$cekuser->jabatan->nama_jabatan .' '.$cekuser->departemen->nama_dept : 'RB telah disetujui '.$cekuser->jabatan->nama_jabatan .' '.$cekuser->departemen->nama_dept,
                    'DESKRIPSI'     => (!empty($request->alasansetuju)) ? $request->alasansetuju : '-',
                    'ID_USER'       => $cekuser->nik,
                    'CREATED_DATE'  => date('Y-m-d H:i:s')
                ];
            }

            $approval = [
                'STATUS'        => 1,
                'APPROVE_DATE'  => date('Y-m-d H:i:s'),
            ];
            
            TrxRbHeader::where('RB_ID', $request->RB_ID)->update($data);
            TrxRbTracking::insert($tracking);
            TrxRbDetailApproval::where('RB_ID', $request->RB_ID)->where('APPROVE_BY', $cekuser->nik)->update($approval);

            $ceks2 = TrxRbDetailApproval::where('RB_ID', $request->RB_ID)->where('STATUS', 0)->get();
            
            if (count($ceks2) == 0) {
                // export dlu rb nya
                $dataemail = [
                    'RB_ID' => $request->RB_ID
                ];

                // queue export pdf rb
                $job1 = new ExportRB($dataemail);
		        $this->dispatch($job1);

                $data = [
                    'STATUS'        => 6,
                    'UPDATE_USER'   => $cekuser->nik,
                    'UPDATE_DATE'   => date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes(1)))
                ];
                $tracking = [
                    'RB_ID'         => $request->RB_ID,
                    'STATUS'        => 'RB selesai disetujui management',
                    'DESKRIPSI'     => 'Pengajuan telah selesai disetujui oleh management. Selanjutnya Menunggu otorisasi/pencairan',
                    'ID_USER'       => $cekuser->nik,
                    'CREATED_DATE'  => date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes(1)))
                ];

                TrxRbHeader::where('RB_ID', $request->RB_ID)->update($data);
                TrxRbTracking::insert($tracking);

                
                // kirim email ke kudus
                $email = [
                    'RB_ID'         => $request->RB_ID,
                    'cabang'        => $rb->cabang->branch_name,
                    'pemohon'       => $rb->karyawan->nama,
                ];

                // queue email ke admin finance
                $job = new SendNotificationEmailToKudus($email);
		        $this->dispatch($job);
            }else{
                $this->_send_mail($request->RB_ID);
            }

            return redirect()->route('persetujuan')->with('message','RB Berhasil Diupdate!');
        }

        //pengajuan ditolak
        if ($request->STATUS == 5) {
            
            // kirim email tolak
            // $email = [
            //     'RB_ID'         => $request->RB_ID,
            //     'emailto'       => $rb->karyawan->user->email,
            //     'cabang'        => $rb->cabang->branch_name,
            //     'pemohon'       => $rb->karyawan->nama,
            //     'ditolak'       => $cekuser->nama,
            //     'j_ditolak'     => $cekuser->jabatan->nama_jabatan,
            //     'd_ditolak'     => $cekuser->departemen->nama_dept,
            // ];
           
            // update STATUS di header
            $data = [
                'STATUS'        => $request->STATUS,
                'UPDATE_USER'   => $cekuser->nik,
                'UPDATE_DATE'   => date('Y-m-d H:i:s')
            ];
            
            // update tracking
            $tracking = [
                [
                    'RB_ID'         => $request->RB_ID,
                    'STATUS'        => 'Pengajuan ditolak oleh '. $cekuser->jabatan->id == 3 ? $cekuser->jabatan->nama_jabatan : $cekuser->jabatan->id == 8 ? $cekuser->jabatan->nama_jabatan : $cekuser->jabatan->id == 11 ? $cekuser->jabatan->nama_jabatan : $cekuser->jabatan->nama_jabatan .' '.$cekuser->departemen->nama_dept .'!',
                    'DESKRIPSI'     => $request->alasantolak,
                    'ID_USER'       => $cekuser->nik,
                    'CREATED_DATE'  => date('Y-m-d H:i:s')
                ],[
                    'RB_ID'         => $request->RB_ID,
                    'STATUS'        => 'RB closed !',
                    'DESKRIPSI'     => 'Silahkan untuk mengajukan RB baru!',
                    'ID_USER'       => $cekuser->nik,
                    'CREATED_DATE'  => date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes(1)))
                ]
            ];

            // update approval
            $approval = [
                'STATUS'        => 2, //STATUS batal
                'APPROVE_DATE'  => date('Y-m-d H:i:s'),
            ];

            $approval2 = [
                'STATUS'        => 3, //STATUS batal (untuk user yg tidak membatalkan tidak bisa diproses)
                'APPROVE_DATE'  => date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes(1))),
            ];

            TrxRbHeader::where('RB_ID', $request->RB_ID)->update($data);
            TrxRbTracking::insert($tracking);
            TrxRbDetailApproval::where('RB_ID', $request->RB_ID)
                ->where('APPROVE_BY', $cekuser->nik)
                ->update($approval);
            TrxRbDetailApproval::where('RB_ID', $request->RB_ID)
                ->whereNotIn('STATUS', [1])
                ->whereNotIn('APPROVE_BY', [$cekuser->nik])
                ->update($approval2);

            $watolak = [
                'RB_ID'         => $request->RB_ID,
                'wato'          => $rb->karyawan->no_telp,
                'cabang'        => $rb->cabang->branch_name,
                'pemohon'       => $rb->karyawan->nama,
                'ditolak'       => $cekuser->nama,
                'j_ditolak'     => $cekuser->jabatan->nama_jabatan,
                'd_ditolak'     => $cekuser->departemen->nama_dept,
                'FLAG'          => 'TOLAK'
            ]; 
            $job = new SendNotificationWhatsapp($watolak);
            $this->dispatch($job);
            // $job = new SendNotificationEmailTolak($email);
            // $this->dispatch($job);

            return redirect()->route('persetujuan')->with('message','RB Berhasil Dibatalkan!');
        }


        if ($request->STATUS == 0) {

            if(!empty($request->buktitf)){

                $file = $request->buktitf;
                $nama_ft = $request->RB_ID. '-' .$file->getClientOriginalName();
                
                $file->move('buktitf', $nama_ft);
    
                $data = [
                    'STATUS'        => $request->STATUS,
                    'UPDATE_USER'   => $cekuser->nik,
                    'bukti_tf'       => $nama_ft,
                    'UPDATE_DATE'   => date('Y-m-d H:i:s')
                ];
            }else{
                $data = [
                    'STATUS'        => $request->STATUS,
                    'UPDATE_USER'   => $cekuser->nik,
                    'UPDATE_DATE'   => date('Y-m-d H:i:s')
                ];
            }

            $tracking = [
                'RB_ID'         => $request->RB_ID,
                'STATUS'        => 'Dana telah ditransfer',
                'DESKRIPSI'     => 'Pengajuan dana telah ditransfer',
                'ID_USER'       => $cekuser->nik,
                'CREATED_DATE'  => date('Y-m-d H:i:s')
            ];

            $email = [
                'RB_ID'         => $request->RB_ID,
                'emailto'       => $rb->karyawan->user->email,
                'cabang'        => $rb->cabang->branch_name,
                'pemohon'       => $rb->karyawan->nama,
                'FLAG'          => 'SELESAI'
            ];
            
            
            TrxRbHeader::where('RB_ID', $request->RB_ID)->update($data);
            TrxRbTracking::insert($tracking);

            $job = new SendNotificationEmailToPengaju($email);
            $this->dispatch($job);

            return redirect()->route('otorisasi')->with('message','RB Berhasil Diupdate!');
        }
    }
}
