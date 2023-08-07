<?php

use Illuminate\Support\Facades\Route;
use App\TrxRbHeader;
use App\TrxRbDetailApproval;
use App\Karyawan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/','AuthController@index')->name('login');
Route::get('/','Auth\LoginController@showLoginForm')->name('login');
Route::post('/','AuthController@login')->name('login');
// Route::post('/','Auth\LoginController@login')->name('login');
Route::post('/logout','AuthController@logout')->name('logout');
Route::get('downloadrb/{rbid}','PengajuanController@downloadrb')->name('downloadrb');
Route::get('downloadspk/{id}','InventarisPemeliharaanController@downloadspk')->name('downloadspk');

// WhatsApp send message
Route::get('/sendWA', 'WhatsAppController@sendWA');
Route::get('/invoice_json', 'WhatsAppController@invoice_json')->name('invoice_json');
Route::get('/cek_invoice_json', 'WhatsAppController@cek_invoice_json')->name('cek_invoice_json');
Route::post('/post_status', 'WhatsAppController@post_status')->name('post_status');
Route::post('/post_status_invalid', 'WhatsAppController@post_status_invalid')->name('post_status_invalid');

// // Route::group(['middleware' => 'CekloginMiddleware'], function (){
Route::group(['middleware' => 'auth'], function (){
    Route::post('/resetpassword','AuthController@resetpassword')->name('reset.password');
    Route::get('/dashboard', function () { 
        // jika yg login pembuat tiket
        $cekAdmin= Karyawan::whereIn('id_jabatan',[4])->where('nik', Auth::user()->username)->first();
        if (!empty($cekAdmin)) {
            # code...
            $totalRb    = TrxRbHeader::where('created_by', Auth::user()->username)->count();
            $tolak      = TrxRbHeader::where('created_by', Auth::user()->username)->where('status',5)->count();
            $setuju     = TrxRbHeader::where('created_by', Auth::user()->username)->where('status',0)->count();
            $totalDana  = TrxRbHeader::where('created_by', Auth::user()->username)->where('status',0)->sum('total_harga');
        }

        $cekStaff = Karyawan::whereNotIn('id_jabatan',[4])->where('nik', Auth::user()->username)->first();
        if (!empty($cekStaff)) {
            # code...
            $totalRb    = TrxRbDetailApproval::where('approve_by', Auth::user()->username)
                            ->whereNotIn('status',[3])
                            ->count();
            $tolak      = TrxRbDetailApproval::where('approve_by', Auth::user()->username)
                            ->where('status',2)
                            ->count();
            $setuju     = TrxRbDetailApproval::where('approve_by', Auth::user()->username)
                            ->where('status',1)
                            ->count();
            $totalDana  = TrxRbDetailApproval::join('TRX_RB_HEADERS','TRX_RB_HEADERS.rb_id', '=', 'TRX_RB_DETAIL_APPROVALS.rb_id')
                            ->where('TRX_RB_DETAIL_APPROVALS.approve_by', Auth::user()->username)
                            ->where('TRX_RB_HEADERS.status',0)
                            ->sum('TRX_RB_HEADERS.total_harga');
        }

        return view('index', compact('totalRb','tolak','setuju','totalDana'));
    })->name('/home');

    Route::group(['middleware' => 'can:menu_mst_karyawan'], function (){
        Route::get('karyawan','KaryawanController@index')->name('karyawan');
        Route::post('karyawan/simpan','KaryawanController@simpan')->name('karyawan.simpan');
        Route::get('karyawan/{id}/edit','KaryawanController@edit')->name('karyawan.edit');
        Route::post('karyawan/update','KaryawanController@update')->name('karyawan.update');
        Route::delete('karyawan/delete/{id}','KaryawanController@hapus')->name('karyawan.hapus');
    });
    Route::group(['middleware' => 'can:menu_mst_user'], function (){
        Route::get('user','AuthController@index')->name('user');
        Route::post('user/simpan','AuthController@simpan')->name('user.simpan');
        Route::get('user/{id}/edit','AuthController@edit')->name('user.edit');
        Route::post('user/update','AuthController@update')->name('user.update');
        Route::post('user/update/status','AuthController@updateStatus')->name('user.update.status');
        Route::delete('user/delete/{id}','AuthController@hapus')->name('user.hapus');
    });
    Route::group(['middleware' => 'can:menu_mst_grup'], function (){
        Route::get('group','GroupController@index')->name('grup');
        Route::post('group/simpan','GroupController@simpan')->name('grup.simpan');
        // Route::get('user/{id}/edit','AuthController@edit')->name('user.edit');
        // Route::post('user/update','AuthController@update')->name('user.update');
        // Route::delete('user/delete/{id}','AuthController@hapus')->name('user.hapus');
    });
    Route::group(['middleware' => 'can:menu_mst_departement'], function (){
        Route::get('departemen','DepartemenController@index')->name('departemen');
        Route::post('departemen/simpan','DepartemenController@simpan')->name('departemen.simpan');
        Route::get('departemen/{id}/edit','DepartemenController@edit')->name('departemen.edit');
        Route::post('departemen/update','DepartemenController@update')->name('departemen.update');
        Route::delete('departemen/delete/{id}','DepartemenController@hapus')->name('departemen.hapus');
    });
    Route::group(['middleware' => 'can:menu_mst_branch'], function (){
        Route::get('branch','BranchController@index')->name('branch');
        Route::post('branch/simpan','BranchController@simpan')->name('branch.simpan');
        Route::get('branch/{id}/edit','BranchController@edit')->name('branch.edit');
        Route::post('branch/update','BranchController@update')->name('branch.update');
        Route::delete('branch/delete/{id}','BranchController@hapus')->name('branch.hapus');
    });
    Route::group(['middleware' => 'can:menu_mst_jabatan'], function (){
        Route::get('jabatan','JabatanController@index')->name('jabatan');
        Route::post('jabatan/simpan','JabatanController@simpan')->name('jabatan.simpan');
        Route::get('jabatan/{id}/edit','JabatanController@edit')->name('jabatan.edit');
        Route::post('jabatan/update','JabatanController@update')->name('jabatan.update');
        Route::delete('jabatan/delete/{id}','JabatanController@hapus')->name('jabatan.hapus');
    });
    Route::group(['middleware' => 'can:menu_mst_jenis_inventaris'], function (){
        Route::get('inventaris/jenis','InventarisController@masterjenis')->name('inventaris.jenis');
        Route::post('inventaris/jenis/simpan','InventarisController@simpanjenis')->name('inventaris.jenissimpan');
        Route::get('inventaris/{id}/{grup}/jenisedit','InventarisController@editjenis')->name('inventaris.jenisedit');
        Route::post('inventaris/update/jenis','InventarisController@updatejenis')->name('inventaris.jenisupdate');
        Route::delete('inventaris/delete/{id}/jenis/{grup}','InventarisController@hapusjenis')->name('inventaris.groupjenis');
    });
    Route::group(['middleware' => 'can:menu_mst_grup_inventaris'], function (){
        Route::get('inventaris/group','InventarisController@mastergroup')->name('inventaris.group');
        Route::post('inventaris/group/simpan','InventarisController@simpangroup')->name('inventaris.groupsimpan');
        Route::get('inventaris/{id}/groupedit','InventarisController@editgroup')->name('inventaris.groupedit');
        Route::post('inventaris/update/group','InventarisController@updategroup')->name('inventaris.groupupdate');
        Route::delete('inventaris/delete/group/{id}','InventarisController@hapusgroup')->name('inventaris.grouphapus');
    });
    Route::group(['middleware' => 'can:menu_mst_bank'], function (){
        Route::get('bank','BankController@index')->name('bank');
        Route::get('bank/cari', 'BankController@cari')->name('bank.cari');
        Route::post('bank/simpan','BankController@simpan')->name('bank.simpan');
        Route::get('bank/{id}/edit','BankController@edit')->name('bank.edit');
        Route::post('bank/update','BankController@update')->name('bank.update');
        Route::delete('bank/delete/{id}','BankController@hapus')->name('bank.hapus');
    });
    Route::group(['middleware' => 'can:menu_mst_routing_email'], function (){
        Route::get('routing_email','RoutingEmailController@index')->name('routing_email');
        Route::get('routing_email/cari', 'RoutingEmailController@cari')->name('routing_email.cari');
        Route::post('routing_email/simpan','RoutingEmailController@simpan')->name('routing_email.simpan');
        Route::get('routing_email/{id}/edit','RoutingEmailController@edit')->name('routing_email.edit');
        Route::post('routing_email/update','RoutingEmailController@update')->name('routing_email.update');
        Route::delete('routing_email/delete/{id}','RoutingEmailController@hapus')->name('routing_email.hapus');
    });
    
    // rb
    Route::post('bank/getnorekening','PengajuanController@getnorekening');
    Route::post('email/getcabang','PengajuanController@getcabang');
    Route::post('email/getemail','PengajuanController@getemail');
    Route::get('/emailtest', function ()
    {
        return view('rb_formated');
    });
    Route::group(['middleware' => 'can:menu_query_rb'], function (){
        Route::get('queryrb','QueryrbController@index')->name('queryrb');
        Route::get('queryrb/liveSearch','QueryrbController@liveSearch')->name('queryrb.liveSearch');
    });
    Route::group(['middleware' => 'can:menu_pengajuan'], function (){
        Route::get('pengajuan/riwayat','PengajuanController@list')->name('pengajuan.list');
        Route::get('pengajuan/liveSearch','PengajuanController@liveSearch')->name('pengajuan.liveSearch');
        Route::get('pengajuan/koreksi','PengajuanController@koreksi')->name('pengajuan.koreksi');
        Route::get('pengajuan/koreksi/{id}','PengajuanController@detailkoreksi')->name('pengajuan.detail.koreksi');
        Route::post('pengajuan/koreksi/simpan','PengajuanController@simpanKoreksi')->name('pengajuanKoreksi.simpan');
        Route::get('pengajuan','PengajuanController@index')->name('pengajuan.baru');
        Route::post('pengajuan/simpan','PengajuanController@simpan')->name('pengajuan.simpan');
    });
    Route::get('pengajuan/detail/{id}','PengajuanController@detail')->name('pengajuan.detail');
    Route::post('persetujuan/update','PersetujuanController@updateStatus')->name('persetujuan.update');
    Route::group(['middleware' => 'can:menu_persetujuan'], function (){
        Route::get('persetujuan','PersetujuanController@index')->name('persetujuan');
        Route::get('riwayat/persetujuan','PersetujuanController@riwayat')->name('persetujuan.riwayat');
        Route::get('persetujuan/liveSearch','PersetujuanController@liveSearch')->name('persetujuan.liveSearch');
    });
    Route::group(['middleware' => 'can:menu_pengesahan'], function (){
        Route::get('pengesahan','PengesahanController@index')->name('pengesahan');
        Route::get('pengesahan/liveSearch','PengesahanController@liveSearch')->name('pengesahan.liveSearch');
        Route::post('pengesahan/update','PengesahanController@updateStatus')->name('pengesahan.update');
        Route::get('riwayat/pengesahan','PengesahanController@riwayat')->name('pengesahan.riwayat');
    });
    Route::group(['middleware' => 'can:menu_otorisasi'], function (){
        Route::get('otorisasi','OtorisasiController@index')->name('otorisasi');
        Route::post('otorisasi/update','OtorisasiController@update')->name('otorisasi.update');
        Route::get('riwayat/otorisasi','OtorisasiController@riwayat')->name('otorisasi.riwayat');
        Route::get('otorisasi/liveSearch','OtorisasiController@liveSearch')->name('otorisasi.liveSearch');
    });
   
    Route::get('logout','AuthController@logout')->name('logout');
});



Route::get('/tes', function ()
{
    return view('email.newTicketAdmin');
});


Route::get('/home', 'HomeController@index')->name('home');
?>