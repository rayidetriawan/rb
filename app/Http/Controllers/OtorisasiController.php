<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TrxRbHeader;

class OtorisasiController extends Controller
{
    public function index()
    {
        $data = TrxRbHeader::where('status',6)
                ->orderBy('created_date', 'DESC')
                ->paginate(10);
        
        return view('transaksi.otorisasi_list', compact('data'));
    }
    public function riwayat()
    {
        $data = TrxRbHeader::where('status',0)
                ->orderBy('update_date', 'DESC')
                ->paginate(10);
        $cabang = Branch::orderby('branch_name', 'asc')->get();
        
        return view('transaksi.otorisasi_riwayat', compact('data','cabang'));
    }
    public function liveSearch(Request $request)
    {
        $output="";
        $search = $request->get('search');
        $user = Karyawan::where('nik', '=', Auth::user()->username)->first();
        if ($request->ajax()) {

            $data = TrxRbHeader::where('status' , '0'); // otorisasi selesai

            if (!empty($request->get('cabang'))) {
                $data->where('branch_id', substr($request->get('cabang'), 0, 3));
            }
            
            if (!empty($request->get('fromdate')) || !empty($request->get('todate'))) {
                $data->whereBetween('created_date', [date('Y-m-d', strtotime($request->get('fromdate'))), date('Y-m-d', strtotime($request->get('todate').'+ 1 day')) ]);
            }
            if (!empty($request->get('search'))) {
                $data->where('rb_id', 'LIKE', "%$search%");
            }

            $datafix = $data->orderBy('update_date', 'DESC')->get();

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
                $output.='<tr>'.
                        '<td>'.$no.'</td>'.
                        '<td>'.$hist->rb_id.'</td>'.
                        '<td>'.$hist->karyawan['nama'].'</td>'.
                        '<td>'.$hist->cabang['branch_name'].'</td>'.
                        '<td>'.$status.'</td>'.
                        '<td>'.$hist->lastupdateby['nama'].'</td>'.
                        '<td>'.date('d/m/Y H:i', strtotime($hist->created_date)).'</td>'.
                        '<td><a href="'.route("pengajuan.detail", $hist->rb_id).'" class="btn btn-sm btn-light">Detail</a></td>'.
                    '</tr>';
            }

            return Response($output);
        }
    }
}
