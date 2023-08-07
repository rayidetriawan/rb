<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index()
    {
        $data = Bank::paginate(10);
        
        return view('master.bank', compact('data'));
    }
    public function simpan(Request $request)
    {
        $data = [
            'bank_name'         => $request->bank,
            'create_user'       => Auth::user()->nik,
            'create_date'       => date('Y-m-d H:i:s'), 
            'status'            => 1,
            'bank_account_no'   => $request->norek,
            'bank_account_name' => $request->nama,
        ];

        Bank::insert($data);

        return redirect()->route('bank')->with('message','Data Berhasil Disimpan!');
        
    }
    public function hapus($id)
    {
        Bank::where('id', $id)->delete();

        return redirect()->back()->with('message','Data Berhasil Dihapus!');
    }
}
