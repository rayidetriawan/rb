<?php

namespace App\Exports;

use App\TrxInventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventarisExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $cabang;
    protected $fromdate;
    protected $todate;

    function __construct($cabang, $fromdate, $todate) {
            $this->cabang   = $cabang;
            $this->fromdate = $fromdate;
            $this->todate   = $todate;
    }
    public function collection()
    {
        $data = TrxInventory::select('INTRAMITRA.TRX_INVENTORY.inventory_id','INTRAMITRA.TRX_INVENTORY.item as nama_inventaris','INTRAMITRA.TRX_INVENTORY.deskripsi_item','MITRA.MST_GROUP_INVENTARIS.group_name as inventaris_grup','MITRA.MST_JENIS_INVENTARIS.nama_jenis as inventaris_jenis','INTRAMITRA.TRX_INVENTORY.label_name','INTRAMITRA.TRX_INVENTORY.qty','INTRAMITRA.TRX_INVENTORY.harga_beli','INTRAMITRA.TRX_INVENTORY.created_date as inventaris_date','INTRAMITRA.TRX_INVENTORY.rb_id','INTRAMITRA.TRX_RB_HEADER.created_date as rb_date','INTRAMITRA.MST_KARYAWAN.nama as create_user','MITRA.VI_MST_BRANCH_KONSOL.branch_name as branch', \DB::raw("CASE WHEN INTRAMITRA.TRX_INVENTORY.status = '1' THEN 'Close' ELSE 'Asset Sales' END AS status_inventaris"));

        if ($this->cabang != 'All') {
            # code...
            $cabang = substr($this->cabang,0,3);
            $schema = substr($this->cabang,4);

            $data->where('INTRAMITRA.TRX_INVENTORY.branch_id', $cabang)->where('INTRAMITRA.TRX_INVENTORY.schema', $schema);
            $data->join('MITRA.MST_JENIS_INVENTARIS', function ($join) {
                $join->on('MITRA.MST_JENIS_INVENTARIS.jenis_id', '=', 'INTRAMITRA.TRX_INVENTORY.jenis_id')
                     ->on('MITRA.MST_JENIS_INVENTARIS.group_id', '=', 'INTRAMITRA.TRX_INVENTORY.grup_id');
            });
            $data->join('MITRA.MST_GROUP_INVENTARIS', 'MITRA.MST_GROUP_INVENTARIS.group_id', '=', 'MITRA.MST_JENIS_INVENTARIS.group_id');
            $data->leftjoin('INTRAMITRA.TRX_RB_HEADER', 'INTRAMITRA.TRX_RB_HEADER.rb_id', '=', 'INTRAMITRA.TRX_INVENTORY.rb_id');
            $data->leftjoin('INTRAMITRA.MST_KARYAWAN', 'INTRAMITRA.MST_KARYAWAN.nik', '=', 'INTRAMITRA.TRX_INVENTORY.created_by');
            $data->join('MITRA.VI_MST_BRANCH_KONSOL', function ($join) {
                $join->on('MITRA.VI_MST_BRANCH_KONSOL.branch_id', '=', 'INTRAMITRA.TRX_INVENTORY.branch_id')
                     ->on('MITRA.VI_MST_BRANCH_KONSOL.schema_name', '=', 'INTRAMITRA.TRX_INVENTORY.schema');
            });

            if (!empty($this->fromdate)) {
                # code...
                $data = $data->where('INTRAMITRA.TRX_INVENTORY.created_date', '>=', $this->fromdate);
            }
            if (!empty($this->todate)) {
                # code...
                $todate = date('Y-m-d', strtotime($this->todate.'+1 day'));
                $data = $data->where('INTRAMITRA.TRX_INVENTORY.created_date', '<=', $todate);
            }
            
        }else{
            $data->join('MITRA.MST_JENIS_INVENTARIS', function ($join) {
                $join->on('MITRA.MST_JENIS_INVENTARIS.jenis_id', '=', 'INTRAMITRA.TRX_INVENTORY.jenis_id')
                     ->on('MITRA.MST_JENIS_INVENTARIS.group_id', '=', 'INTRAMITRA.TRX_INVENTORY.grup_id');
            });
            $data->join('MITRA.MST_GROUP_INVENTARIS', 'MITRA.MST_GROUP_INVENTARIS.group_id', '=', 'MITRA.MST_JENIS_INVENTARIS.group_id');
            $data->leftjoin('INTRAMITRA.TRX_RB_HEADER', 'INTRAMITRA.TRX_RB_HEADER.rb_id', '=', 'INTRAMITRA.TRX_INVENTORY.rb_id');
            $data->leftjoin('INTRAMITRA.MST_KARYAWAN', 'INTRAMITRA.MST_KARYAWAN.nik', '=', 'INTRAMITRA.TRX_INVENTORY.created_by');
            $data->join('MITRA.VI_MST_BRANCH_KONSOL', function ($join) {
                $join->on('MITRA.VI_MST_BRANCH_KONSOL.branch_id', '=', 'INTRAMITRA.TRX_INVENTORY.branch_id')
                     ->on('MITRA.VI_MST_BRANCH_KONSOL.schema_name', '=', 'INTRAMITRA.TRX_INVENTORY.schema');
            });

            if (!empty($this->fromdate)) {
                # code...
                $data = $data->where('INTRAMITRA.TRX_INVENTORY.created_date', '>=', $this->fromdate);
            }
            if (!empty($this->todate)) {
                # code...
                $todate = date('Y-m-d', strtotime($this->todate.'+1 day'));
                $data = $data->where('INTRAMITRA.TRX_INVENTORY.created_date', '<=', $todate);
            }
        }
        return $data->get();
    }
    // public function collection()
    // {
    //     $data = TrxInventory::select('SBXINTRAMITRA.TRX_INVENTORY.inventory_id','SBXINTRAMITRA.TRX_INVENTORY.item as nama_inventaris','SBXINTRAMITRA.TRX_INVENTORY.deskripsi_item','MITRA.MST_GROUP_INVENTARIS.group_name as inventaris_grup','MITRA.MST_JENIS_INVENTARIS.nama_jenis as inventaris_jenis','SBXINTRAMITRA.TRX_INVENTORY.label_name','SBXINTRAMITRA.TRX_INVENTORY.qty','SBXINTRAMITRA.TRX_INVENTORY.harga_beli','SBXINTRAMITRA.TRX_INVENTORY.created_date as inventaris_date','SBXINTRAMITRA.TRX_INVENTORY.rb_id','SBXINTRAMITRA.TRX_RB_HEADER.created_date as rb_date','SBXINTRAMITRA.MST_KARYAWAN.nama as create_user','MITRA.VI_MST_BRANCH_KONSOL.branch_name as branch', \DB::raw("CASE WHEN SBXINTRAMITRA.TRX_INVENTORY.status = '1' THEN 'Close' ELSE 'Asset Sales' END AS status_inventaris"));

    //     if ($this->cabang != 'All') {
    //         # code...
    //         $cabang = substr($this->cabang,0,3);
    //         $schema = substr($this->cabang,4);

    //         $data->where('SBXINTRAMITRA.TRX_INVENTORY.branch_id', $cabang)->where('SBXINTRAMITRA.TRX_INVENTORY.schema', $schema);
    //         $data->join('MITRA.MST_JENIS_INVENTARIS', function ($join) {
    //             $join->on('MITRA.MST_JENIS_INVENTARIS.jenis_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.jenis_id')
    //                  ->on('MITRA.MST_JENIS_INVENTARIS.group_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.grup_id');
    //         });
    //         $data->join('MITRA.MST_GROUP_INVENTARIS', 'MITRA.MST_GROUP_INVENTARIS.group_id', '=', 'MITRA.MST_JENIS_INVENTARIS.group_id');
    //         $data->leftjoin('SBXINTRAMITRA.TRX_RB_HEADER', 'SBXINTRAMITRA.TRX_RB_HEADER.rb_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.rb_id');
    //         $data->leftjoin('SBXINTRAMITRA.MST_KARYAWAN', 'SBXINTRAMITRA.MST_KARYAWAN.nik', '=', 'SBXINTRAMITRA.TRX_INVENTORY.created_by');
    //         $data->join('MITRA.VI_MST_BRANCH_KONSOL', function ($join) {
    //             $join->on('MITRA.VI_MST_BRANCH_KONSOL.branch_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.branch_id')
    //                  ->on('MITRA.VI_MST_BRANCH_KONSOL.schema_name', '=', 'SBXINTRAMITRA.TRX_INVENTORY.schema');
    //         });

    //         if (!empty($this->fromdate)) {
    //             # code...
    //             $data = $data->where('SBXINTRAMITRA.TRX_INVENTORY.created_date', '>=', $this->fromdate);
    //         }
    //         if (!empty($this->todate)) {
    //             # code...
    //             $todate = date('Y-m-d', strtotime($this->todate.'+1 day'));
    //             $data = $data->where('SBXINTRAMITRA.TRX_INVENTORY.created_date', '<=', $todate);
    //         }
            
    //     }else{
    //         $data->join('MITRA.MST_JENIS_INVENTARIS', function ($join) {
    //             $join->on('MITRA.MST_JENIS_INVENTARIS.jenis_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.jenis_id')
    //                  ->on('MITRA.MST_JENIS_INVENTARIS.group_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.grup_id');
    //         });
    //         $data->join('MITRA.MST_GROUP_INVENTARIS', 'MITRA.MST_GROUP_INVENTARIS.group_id', '=', 'MITRA.MST_JENIS_INVENTARIS.group_id');
    //         $data->leftjoin('SBXINTRAMITRA.TRX_RB_HEADER', 'SBXINTRAMITRA.TRX_RB_HEADER.rb_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.rb_id');
    //         $data->leftjoin('SBXINTRAMITRA.MST_KARYAWAN', 'SBXINTRAMITRA.MST_KARYAWAN.nik', '=', 'SBXINTRAMITRA.TRX_INVENTORY.created_by');
    //         $data->join('MITRA.VI_MST_BRANCH_KONSOL', function ($join) {
    //             $join->on('MITRA.VI_MST_BRANCH_KONSOL.branch_id', '=', 'SBXINTRAMITRA.TRX_INVENTORY.branch_id')
    //                  ->on('MITRA.VI_MST_BRANCH_KONSOL.schema_name', '=', 'SBXINTRAMITRA.TRX_INVENTORY.schema');
    //         });

    //         if (!empty($this->fromdate)) {
    //             # code...
    //             $data = $data->where('SBXINTRAMITRA.TRX_INVENTORY.created_date', '>=', $this->fromdate);
    //         }
    //         if (!empty($this->todate)) {
    //             # code...
    //             $todate = date('Y-m-d', strtotime($this->todate.'+1 day'));
    //             $data = $data->where('SBXINTRAMITRA.TRX_INVENTORY.created_date', '<=', $todate);
    //         }
    //     }
    //     return $data->get();
    // }
    public function headings(): array
    {
        return ["inventaris id", "nama inventaris", "deskripsi","grup","jenis","label qr","qty","harga beli","inventaris date","rb id","rb date","dibuat oleh","inventaris lokasi","status"];
    }
}
