<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Karyawan;
use App\Departemen;
use App\Branch;
use App\Jabatan;
use App\KaryawanOracle;

use DB;

class KaryawanController extends Controller
{
    public function index()
    {
        $data   = Karyawan::paginate(10);
        $dep    = Departemen::all();
        $branch = Branch::orderBy('branch_name', 'ASC')->get();
        $jabatan = Jabatan::all();
            
        return view('master.karyawan', compact('data','dep','branch','jabatan'));
    }

    private function _validation(Request $request){
        $validate = $request->validate([
            'nama'      => 'required|max:50|min:2',
            'jk'        => 'required|in:L,P',
            'bagdept'   => 'required',
            'jabatan'   => 'required',
            'branch'   =>  'required',
            'telp'      => 'required|max:15|min:9',
        ],
        [
            'nama.required'      => 'Bidang ini tidak boleh kosong !',
            'jk.required'        => 'Bidang ini tidak boleh kosong !',
            'bagdept.required'   => 'Bidang ini tidak boleh kosong !',
            'telp.required'      => 'Bidang ini tidak boleh kosong !',
            'branch.required'      => 'Bidang ini tidak boleh kosong !',
            'jabatan.required'      => 'Bidang ini tidak boleh kosong !',

            'nama.max' => 'Maksimal 50 Karakter!',
            'nama.min' => 'Minimal 2 Karakter!',

            'telp.max' => 'Maksimal 15 Karakter!',
            'telp.min' => 'Minimal 9 Karakter!',
        ]);
    }

    public static function _nik(){
        $q= Karyawan::select(DB::raw('MAX(RIGHT(nik,4)) as nik'))->get();
       
        if($q)
        {
            foreach($q as $k)
            {
                $tmp = ((int)$k->nik)+1;
                $kd = "TUA".sprintf("%04s", $tmp);
            }
        }
        else
        {
            $kd = "TUA0001";
        }

        return $kd;
    }

    public function simpan(Request $request)
    {
        
        $this->_validation($request);
        $cabang = $request->branch;
        $nik    = $this->_nik();
        // foreach($request->branch as )
        $data = [
            'nik'       => $nik,
            'nama'      => $request->nama,
            'jk'        => $request->jk,
            'id_bag_dept'  => $request->bagdept,
            'id_jabatan'   => $request->jabatan,
            'branch_id'    => $cabang,
            'no_telp'      => $request->telp,
            'created_at' => date('Y-m-d H:i:s'), 
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Karyawan::insert($data);

        return redirect()->route('karyawan')->with('message','Data Berhasil Disimpan!');
    }

    public function edit($id)
    {
        $data = Karyawan::where('nik', '=', $id)->first();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        // dd($request);   
        $request->validate([
            'edit_nama'      => 'required|max:50|min:2',
            'edit_jk'        => 'required|in:L,P',
            'edit_bagdept'   => 'required',
            'edit_telp'      => 'required|max:15|min:9',
        ],
        [
            'edit_nama.required'      => 'Bidang ini tidak boleh kosong !',
            'edit_jk.required'        => 'Bidang ini tidak boleh kosong !',
            'edit_bagdept.required'   => 'Bidang ini tidak boleh kosong !',
            'edit_telp.required'      => 'Bidang ini tidak boleh kosong !',

            'edit_nama.max' => 'Maksimal 50 Karakter!',
            'edit_nama.min' => 'Minimal 2 Karakter!',

            'edit_telp.max' => 'Maksimal 15 Karakter!',
            'edit_telp.min' => 'Minimal 9 Karakter!',
        ]);

        $data = [
            'nama'      => $request->edit_nama,
            'jk'        => $request->edit_jk,
            'id_bag_dept'  => $request->edit_bagdept,
            'no_telp'      => $request->edit_telp,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        Karyawan::where('nik', $request->id)->update($data);

        return redirect()->route('karyawan')->with('message','Data '.$request->edit_nama.' Berhasil Diubah!');

    }

    public function hapus($id)
    {
        Karyawan::where('nik', $id)->delete();

        return redirect()->back()->with('message','Data Berhasil Dihapus!');
    }

}
