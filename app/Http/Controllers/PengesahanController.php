<?php

namespace App\Http\Controllers;

use App\Branch;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TrxRbHeader;
use App\TrxRbTracking;
use App\TrxRbDetailApproval;
use App\Karyawan;

class PengesahanController extends Controller
{
    public function index()
    {
        // khusus untuk admin finance
        if(Auth::user()->ugrup->groupId == 29){

            $data = TrxRbHeader::where('status',2)
                    ->orderBy('created_date', 'DESC')
                    ->paginate(10);
        }else{
            $data = [];
        }
        
        return view('transaksi.pengesahan_list', compact('data'));
    }
    public function riwayat()
    {
        if(Auth::user()->ugrup->groupId == 29){
            $data = TrxRbHeader::whereNotIn('status',[1,2])
                ->orderBy('update_date', 'DESC')
                ->paginate(10);
        }else{
            $data = [];
        }
        $cabang = Branch::orderby('branch_name', 'asc')->get();

        return view('transaksi.pengesahan_riwayat', compact('data','cabang'));
    }
    public function liveSearch(Request $request)
    {
        $search = $request->get('search');
        $user = Karyawan::where('nik', '=', Auth::user()->username)->first();
        if ($request->ajax()) {

            $data = TrxRbHeader::select('TRX_RB_HEADER.rb_id','TRX_RB_HEADER.status','TRX_RB_HEADER.total_harga','TRX_RB_HEADER.update_user','TRX_RB_HEADER.update_date', 'TRX_RB_HEADER.CREATED_BY', 'TRX_RB_HEADER.branch_id','TRX_RB_HEADER.created_date','TRX_RB_HEADER.schema_name')->join('TRX_RB_DETAIL_ITEM', 'TRX_RB_DETAIL_ITEM.rb_id', '=', 'TRX_RB_HEADER.rb_id');

            if (!empty($request->get('status'))) {
                if($request->get('status') == 'O'){
                    $data->where('TRX_RB_HEADER.status', '0');
                }else{
                    $data->where('TRX_RB_HEADER.status', $request->get('status'));
                }
            }

            if (!empty($request->get('cabang'))) {
                $data->where('TRX_RB_HEADER.branch_id', substr($request->get('cabang'), 0, 3)) 
                    ->where('TRX_RB_HEADER.schema_name', substr($request->get('cabang'), 4));
            }
            
            if (!empty($request->get('fromdate')) || !empty($request->get('todate'))) {
                $data->whereBetween('TRX_RB_HEADER.created_date', [date('Y-m-d', strtotime($request->get('fromdate'))), date('Y-m-d', strtotime($request->get('todate').'+ 1 day')) ]);
            }
            if (!empty($request->get('search'))) {
                $data->where('TRX_RB_HEADER.rb_id', 'LIKE', "%$search%");
                $data->orWhere('TRX_RB_HEADER.total_harga', 'LIKE', "%$search%");
                $search1 = strtoupper($search);
                $data->orWhere(DB::raw('UPPER(TRX_RB_DETAIL_ITEM.item)'), 'like', "%$search1%");
            }

            $data->groupBy('TRX_RB_HEADER.rb_id','TRX_RB_HEADER.status','TRX_RB_HEADER.total_harga','TRX_RB_HEADER.update_user','TRX_RB_HEADER.update_date','TRX_RB_HEADER.CREATED_BY', 'TRX_RB_HEADER.branch_id','TRX_RB_HEADER.created_date','TRX_RB_HEADER.schema_name');

            $datafix = $data->orderBy('TRX_RB_HEADER.update_date', 'DESC')->get();

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
                foreach($hist->TrxRbDetailItem as $item){
                    $itemfix .='<li>'.$item->item.'<div class="text-muted">'.$item->qty.' x Rp.&nbsp;'.$item->harga.' = Rp.'.$item->qty*$item->harga.'</div></li>';
                };

                $output.='<tr>'.
                        '<td class="align-top">'.$no.'</td>'.
                        '<td class="align-top">'.$hist->rb_id.'<br><div class="text-muted">Branch : '. $hist->cabang['branch_name'].'</div></td>'.
                        '<td class="align-top"><ul class="ps-2 vstack mb-1">'.$itemfix.'</ul><br>Total = Rp.&nbsp;'.$hist->total_harga.'</td>'.
                        '<td class="align-top">'.$status.'</td>'.
                        '<td class="align-top">'.$hist->lastupdateby['nama'].'</td>'.
                        '<td class="align-top">'.date('d/m/Y H:i', strtotime($hist->created_date)).'</td>'.
                        '<td class="align-top text-center"><a href="'.route("pengajuan.detail", $hist->rb_id).'" class="btn btn-sm btn-light">Detail</a></td>'.
                    '</tr>';
            }

            return Response($output);
        }
    }
}
