@extends('layouts.master')
@section('title', 'Detail Pengajuan')
@section('nav_active_rb_head', 'active')
@section('nav_active_persetujuan', 'active')
@section('content')
    <div class="modal fade zoomIn" id="modalsetuju" tabindex="-1" aria-labelledby="exampleModalgridLabel" data-v-01bddeea="" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalgridLabel">Setujui Pengajuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('persetujuan.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="RB_ID" id="id" value="{{ old('id') }}">
                        <input type="hidden" name="id_user" id="user" value="{{ old('user') }}">
                        <input type="hidden" name="STATUS" id="STATUS" value="{{ old('STATUS') }}">
                        <div class="row g-3">

                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label @error('alasansetuju') text-danger @enderror">Tambah Keterangan <i class="text-muted">(optional)</i> </label>
                                        <textarea class="form-control @error('alasansetuju') is-invalid @enderror" name="alasansetuju" id="alasansetuju" rows="3">{{ old('alasantolak') }}</textarea>

                                    @error('alasansetuju')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button class="btn btn-primary" type="submit" id="btnsetujuikcb">Submit</button>
                                    <button id="loadingkcb" style="display:none;" type="button" class="btn btn-primary btn-load mt-1 mb-1" disabled>
                                        <span class="d-flex align-items-center">
                                            <span class="spinner-border flex-shrink-0" role="STATUS">
                                                <span class="visually-hidden">Loading...</span>
                                            </span>
                                            <span class="flex-grow-1 ms-2">
                                                Loading...
                                            </span>
                                        </span>
                                    </button>
                                    
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>

                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade zoomIn" id="modaltolak" tabindex="-1" aria-labelledby="exampleModalgridLabel" data-v-01bddeea=""
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalgridLabel">Tolak Pengajuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('persetujuan.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="RB_ID" class="id" id="id" value="{{ old('id') }}">
                        <input type="hidden" name="id_user" class="user" id="user" value="{{ old('user') }}">
                        <input type="hidden" value="5" name="STATUS">
                        <div class="row g-3">

                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label @error('alasantolak') text-danger @enderror">Alasan Pengajuan Ditolak</label>
                                        <textarea required class="form-control @error('alasantolak') is-invalid @enderror" name="alasantolak" id="alasantolak" rows="3">{{ old('alasantolak') }}</textarea>

                                    @error('alasantolak')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <div id="loadingedit" style="display:none;">
                                        <button type="button" class="btn btn-primary btn-load" disabled>
                                            <span class="d-flex align-items-center">
                                                <span class="spinner-border flex-shrink-0" role="STATUS">
                                                    <span class="visually-hidden">Loading...</span>
                                                </span>
                                                <span class="flex-grow-1 ms-2">
                                                    Loading...
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>

                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade zoomIn" id="modaltolakadminho" tabindex="-1" aria-labelledby="exampleModalgridLabel" data-v-01bddeea=""
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalgridLabel">Tolak Pengajuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('persetujuan.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="RB_ID" id="idtlk" value="{{ old('idtlk') }}">
                        <input type="hidden" name="id_user" id="usertlk" value="{{ old('usertlk') }}">
                        <input type="hidden" value="35" name="STATUS">
                        <div class="row g-3">

                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label @error('alasantolak') text-danger @enderror">Alasan Pengajuan Ditolak</label>
                                        <textarea required class="form-control @error('alasantolak') is-invalid @enderror" name="alasantolak" id="alasantolaktlk" rows="3">{{ old('alasantolak') }}</textarea>

                                    @error('alasantolak')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <div id="loadingedit" style="display:none;">
                                        <button type="button" class="btn btn-primary btn-load" disabled>
                                            <span class="d-flex align-items-center">
                                                <span class="spinner-border flex-shrink-0" role="STATUS">
                                                    <span class="visually-hidden">Loading...</span>
                                                </span>
                                                <span class="flex-grow-1 ms-2">
                                                    Loading...
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>

                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade zoomIn" id="modalkoreksi" tabindex="-1" aria-labelledby="exampleModalgridLabel" data-v-01bddeea=""
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalgridLabel">Koreksi Pengajuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('persetujuan.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="RB_ID" id="idrb" value="{{ old('idrb') }}">
                        <input type="hidden" name="id_user" id="userkoreksi" value="{{ old('userkoreksi') }}">
                        {{-- STATUS koreksi --}}
                        <input type="hidden" value="6" name="STATUS"> 
                        <div class="row g-3">

                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label ">Apa yang harus dikoreksi ? </label>
                                    
                                    <!-- Base Example -->
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="item" name="item">
                                        <label class="form-check-label" for="item">
                                            Item
                                        </label>
                                    </div>
                                    <!-- Base Example -->
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="harga" name="harga">
                                        <label class="form-check-label" for="harga">
                                            Harga
                                        </label>
                                    </div>
                                    <!-- Base Example -->
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="total" name="total">
                                        <label class="form-check-label" for="total">
                                            Jumlah / Total
                                        </label>
                                    </div>
                                    <!-- Base Example -->
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="rekening" name="rekening">
                                        <label class="form-check-label" for="rekening">
                                            Informasi Rekening
                                        </label>
                                    </div>
                                    <!-- Base Example -->
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="note" name="note">
                                        <label class="form-check-label" for="note">
                                            Note
                                        </label>
                                    </div>
                                    <!-- Base Example -->
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="lampiranfile" name="lampiran">
                                        <label class="form-check-label" for="lampiranfile">
                                            Lampiran File
                                        </label>
                                    </div>
                                    <!-- Base Example -->
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" id="lainlain" name="lainlain">
                                        <label class="form-check-label" for="lainlain">
                                            Lain-lain
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label ">Tambahkan catatan <i class="text-danger">*</i></label>
                                        <textarea required class="form-control" name="alasankoreksi" id="alasankoreksi" rows="3">{{ old('alasankoreksi') }}</textarea>
                                </div>
                            </div>

                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <div id="loadingedit" style="display:none;">
                                        <button type="button" class="btn btn-primary btn-load" disabled>
                                            <span class="d-flex align-items-center">
                                                <span class="spinner-border flex-shrink-0" role="STATUS">
                                                    <span class="visually-hidden">Loading...</span>
                                                </span>
                                                <span class="flex-grow-1 ms-2">
                                                    Loading...
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>

                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4 mx-n4 mb-n5">
                <div class="bg-soft-success">
                    <div class="card-body pb-4 mb-5">
                        <div class="row">
                            <div class="col-md mt-5 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-md-auto">

                                    </div>
                                    <!--end col-->
                                    <div class="col-md">
                                        <h4 class="fw-semibold" id="ticket-title">#{{ $data->RB_ID }}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div class="text-muted"><i class="ri-building-line align-bottom me-1"></i><span
                                                    id="ticket-client">{{ $data->cabang['branch_name'] }}</span></div>
                                            <div class="vr"></div>
                                            <div class="text-muted">Create Date : <span class="fw-medium "
                                                    id="create-date">{{ $data->CREATED_DATE }}</span></div>
                                                                                        
                                        </div>
                                    </div>
                                    @if($data->STATUS == 0 || $data->STATUS == 6)
                                    <div class="col-12 col-lg-auto order-last order-lg-0">
                                        <div class="row text text-white-50 text-center">
                                            <div class="col-sm-12 col-4">
                                                <div class="p-2">
                                                    <div class="flex-shrink-0">
                                                        <a href="{{ route('downloadrb', $data->RB_ID) }}" class="btn btn-success"><i class="ri-download-2-line align-bottom"></i> &nbsp;Download File RB</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div><!-- end card body -->
                </div>
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-medium">ID</td>
                                    <td>{{ $data->RB_ID }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">By</td>
                                    <td id="t-client">{{ $data->karyawan->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-medium">Date</td>
                                    <td> {{ $data->CREATED_DATE }}</td>
                                </tr>
                                {{-- <tr>
                                    <td class="fw-medium">Last Update</td>
                                    <td id="t-client">{{ $data->update_user->karyawan->nama }}</td>
                                </tr> --}}
                                <tr>
                                    <td class="fw-medium">Update</td>
                                    <td>
                                        @if($data->UPDATE_DATE) 
                                            {{ $data->UPDATE_DATE }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @if($data->STATUS == 0)
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:120px;height:120px"></lord-icon>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="fw-medium">STATUS</td>
                                    <td>
                                        @if ($data->STATUS == 1)
                                            <span class="badge badge-soft-secondary badge-border text-wrap">
                                                Menunggu Aproval Kacab/ Kabeng
                                            </span>
                                        @elseif($data->STATUS == 2)
                                            <span class="badge badge-soft-secondary badge-border text-wrap">
                                                Proses Validasi Pengesahan
                                            </span>
                                        @elseif($data->STATUS == 3)
                                            <span class="badge badge-soft-secondary badge-border text-wrap">
                                                Menunggu Aproval Terkait
                                            </span>
                                        @elseif($data->STATUS == 4)
                                            <span class="badge badge-soft-secondary badge-border text-wrap">
                                                Menunggu Approval Management Terkait
                                            </span>
                                        @elseif($data->STATUS == 5)
                                            <span class="badge badge-soft-danger badge-border text-wrap">
                                                Ditolak
                                            </span>
                                        @elseif($data->STATUS == 6)
                                            <span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                                Menunggu otorisasi
                                            </span>
                                        @elseif($data->STATUS == 7)
                                            <span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                                RB Harus Dikoreksi
                                            </span>
                                        @elseif($data->STATUS == 0)
                                            <span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                                RB telah selesai 
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0">Files Attachment</h6>
                </div>
                <div class="card-body">
                    @if($data->TrxRbDetailDokumen)
                        @foreach ($data->TrxRbDetailDokumen as $file)
                            <div class="d-flex align-items-center border border-dashed p-2 mt-2 rounded">
                                <div class="flex-shrink-0 avatar-sm">
                                    <div class="avatar-title bg-light rounded">
                                        <i class="far fa-file-alt fs-5 text-danger fs-20"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><a href="javascript:void(0);">{{ $file->DOKUMEN_NAME }}</a></h6>
                                </div>
                                <div class="hstack gap-3 fs-16">
                                    <a href="{{ asset('dokumen/' . $file->DOKUMEN_NAME) }}" target="_blank" class="text-muted"><i
                                            class="mdi mdi-open-in-new"></i></a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @if($data->STATUS == 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title fw-semibold mb-0">Bukti Transfer</h6>
                </div>
                <div class="card-body">
                    @if($data->BUKTI_TF)
                        <div class="d-flex align-items-center border border-dashed p-2 mt-2 rounded">
                            <div class="flex-shrink-0 avatar-sm">
                                <div class="avatar-title bg-light rounded">
                                    <i class="fa-solid fa-file-invoice-dollar text-info fs-20"></i>
                                    {{-- <i class="fa fa-file-pdf-o text-danger fs-20"></i> --}}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><a href="javascript:void(0);">{{ $data->BUKTI_TF }}</a></h6>
                            </div>
                            <div class="hstack gap-3 fs-16">
                                <a href="{{ asset('buktitf/' . $data->BUKTI_TF) }}" target="_blank" class="text-muted"><i
                                        class="mdi mdi-open-in-new"></i></a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        <!--end col-->
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header border-bottom-dashed p-4 mb-0">
                    <div class="alert alert-primary mb-0">
                        <p class="mb-0"><span class="fw-semibold">Note:</span>
                            @if(!empty($data->KETERANGAN))
                            <span id="note">@nl2br($data->KETERANGAN)
                            </span>
                            @else
                            {!! $data->KET_CKEDITOR !!}
                            @endif
                        </p>
                    </div>
                    
                </div>
                <!--end card-body-->
                <div class="card-header border-bottom-dashed p-4">
                    <h5 class="card-title mb-3">Tracking RB</h5>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tracking as $no => $item)
                                    <tr>
                                        <td>{{  $no+1 }}</td>
                                        <td>{{ $item->CREATED_DATE }}</td>
                                        <td>
                                            @if ($item->STATUS == 'Solved')
                                                <div class="text-success">
                                                    <i class="ri-checkbox-circle-line fs-17 align-middle"></i>
                                                    {{ $item->STATUS }}
                                                </div>
                                            @elseif($item->STATUS == 'Ditolak')
                                                <div class="text-danger">
                                                    <i class="ri-close-circle-line fs-17 align-middle"></i>
                                                    {{ $item->STATUS }}
                                                </div>
                                            @else
                                                <div class="text-info">
                                                    <i class="ri-checkbox-circle-line fs-17 align-middle"></i>
                                                    {{ $item->STATUS }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->DESKRIPSI)
                                                {{ $item->DESKRIPSI }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xs me-1">
                                                        <span
                                                            class="avatar-title bg-soft-info text-info rounded-circle fs-16">
                                                            <?php
                                                            $s = $item->karyawan['nama'];
                                                            
                                                            if (preg_match_all('/\b(\w)/', strtoupper($s), $m)) {
                                                                $v = implode('', $m[1]);
                                                            }
                                                            
                                                            echo $v;
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    {{ $item->karyawan['nama'] }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-header border-bottom-dashed p-4">
                    <h5 class="card-title mb-3">Detail Item</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless table-nowrap align-middle mb-0">
                            <thead>
                                <tr class="table-active">
                                    <th scope="col" style="width: 50px;">#</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col" class="text-center">QTY</th>
                                    <th scope="col" class="text-end">Harga</th>
                                    <th scope="col" class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="products-list">
                                @if($data->TrxRbDetailItem)
                                @foreach($data->TrxRbDetailItem as $no => $item)
                                <tr>
                                    <th scope="row">{{ $no+1 }}</th>
                                    <td class="text-start">
                                        <span class="fw-medium">{{ $item->ITEM }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->QTY }}</td>
                                    <td class="text-end">@uang($item->HARGA)</td>
                                    <td class="text-end">@uang($item->QTY*$item->HARGA)</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table><!--end table-->
                    </div>
                    <div class="mt-2">
                        <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                            <tbody>
                                <tr class="border-top border-top-dashed fs-15">
                                    <th scope="row" >Total</th>
                                    <th class="text-end">@uang($data->TOTAL_HARGA)</th>
                                </tr>
                            </tbody>
                        </table>
                        <!--end table-->
                    </div>
                    <div class="mt-3">
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">Dana Ditransfer ke:</h6>
                        <p class="text-muted mb-1">Bank: <span class="fw-medium" id="payment-method">{{ $data->BANK }}</span></p>
                        <p class="text-muted mb-1">No. Rekening: <span class="fw-medium" id="card-holder-name">{{ $data->NO_REK }}</span></p>
                        <p class="text-muted mb-1">Atas Nama: <span class="fw-medium" id="card-number">{{ $data->NAMA_REK }}</span></p>
                        
                    </div>
                </div>
                <div class="card-header border-bottom-dashed mb-2 p-4 text-center">
                    {{-- @if($data->STATUS > 1 || $data->STATUS == 0) --}}
                    <div class="row">
                        <div class="col mt-2 text-center">
                            Dibuat Oleh,<br>
                            <i class="ri-check-double-line text-success fs-1"></i>
                            <div class="fs-12">
                                <strong><u>{{ $data->karyawan->nama }}</u><br>
                                {{ $data->karyawan->jabatan->nama_jabatan }}</strong>
                            </div>
                        </div>
                        @foreach($mengetahui as $result)
                        <div class="col mt-2 text-center">
                            Mengetahui,<br>
                            @if($result->STATUS == 1)
                            <i class="ri-check-double-line text-success fs-1"></i>
                            @elseif($result->STATUS == 2)
                            <i class="ri-close-circle-line text-danger fs-1"></i>
                            @elseif($result->STATUS == 3)
                            <i class="ri-stop-circle-line text-warning fs-1"></i>
                            @else
                            <i class="ri-time-line text-warning fs-1"></i>
                            @endif
                            <div class="fs-12">
                                <strong><u>{{ $result->karyawan['nama'] }}</u><br>
                                @if($result->karyawan['jabatan']['id'] == '3' || $result->karyawan['jabatan']['id'] == '10' || $result->karyawan['jabatan']['id'] == '8' || $result->karyawan['jabatan']['id'] == '11' ) {{-- branch head, kabeng, owner, direktur --}}
                                {{ $result->karyawan['jabatan']['nama_jabatan'] }}        
                                @else
                                {{ $result->karyawan['jabatan']['nama_jabatan'] }} {{ $result->karyawan['departemen']['nama_dept'] }}
                                @endif
                                </strong>
                            </div>
                        </div>
                        @endforeach
                        @foreach($menyetujui as $result)
                        <div class="col mt-2 text-center">
                            Menyetujui,<br>
                            @if($result->STATUS == 1)
                            <i class="ri-check-double-line text-success fs-1"></i>
                            @elseif($result->STATUS == 2)
                            <i class="ri-close-circle-line text-danger fs-1"></i>
                            @elseif($result->STATUS == 3)
                            <i class="ri-stop-circle-line text-warning fs-1"></i>
                            @else
                            <i class="ri-time-line text-warning fs-1"></i>
                            @endif
                            <div class="fs-12">
                                <strong><u>{{ $result->karyawan['nama'] }}</u><br>
                                    @if($result->karyawan['jabatan']['id'] == '3' || $result->karyawan['jabatan']['id'] == '8' || $result->karyawan['jabatan']['id'] == '11' ) {{-- branch head, owner, direktur --}}
                                    {{ $result->karyawan['jabatan']['nama_jabatan'] }}
                                    @else
                                    {{ $result->karyawan['jabatan']['nama_jabatan'] }} {{ $result->karyawan['departemen']['nama_dept'] }}
                                    @endif
                                </strong>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- @endif --}}
                </div>
                <div class="card-body mb-2 p-4">
                    {{-- jika user kacab atau menyetujui bersangkutan--}}
                    @if($data->STATUS == 1)
                    @if($user->AuthUsergrup->groupId == 1 || $cek != null)
                    <div class="text-center mb-2">
                        <a href="javascript:void(0)" class="setujui" id="setujui"
                            data-v-cd5f1dea="" data-id="{{ $data->RB_ID  }}" data-user="{{ $user->nik }}" data-status="2">
                            <button type="button" class="btn btn-primary text-center mt-1 mb-1" >
                                <i class="ri-check-double-line align-bottom me-1 "></i>
                                <strong>SETUJUI</strong>

                            </button>
                        </a>
                        <a href="javascript:void(0)" class="tolak" id="tolak"
                            data-v-cd5f1dea="" data-id="{{ $data->RB_ID  }}" data-user="{{ $user->nik }}">
                            <button type="button" class="btn btn-danger text-center mt-1 mb-1" >
                                <i class="ri-close-fill align-bottom me-1 "></i>
                                <strong>TOLAK</strong>

                            </button>
                        </a>
                    </div>
                    @endif
                    @endif
                    {{-- end jika user kacab --}}

                    {{-- jika user admin finance --}}
                    @if($user->AuthUsergrup->groupId == 30)
                    @if($data->STATUS == 2)
                    <form method="POST" action="{{ route('persetujuan.update') }}">
                        @csrf
                        <input type="hidden" name="id_user" value="{{ $user->nik }}">
                        <input type="hidden" name="RB_ID" value="{{ $data->RB_ID }}">
                        <input type="hidden" name="STATUS" value="3">
                        <div class="row">
                            
                            <div class="col-sm-6 mt-2">
                                <div>
                                    <label for="basiInput" class="form-label fs-15">Diketahui oleh</label>
                                    <select class="form-control diketahui" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="diketahui[]" multiple>
                                        <option value="">Pilih</option>
                                        @foreach($diketahui as $result)
                                            @if($result->jabatan->id == '3' || $result->jabatan->id == '8' || $result->jabatan->id == '11' ) {{-- branch head, owner, direktur --}}
                                            <option value="{{ $result->nik }}">{{ $result->jabatan->nama_jabatan }} | {{ $result->nama }}</option>
                                            @else
                                            <option value="{{ $result->nik }}">{{ $result->jabatan->nama_jabatan }} {{ $result->departemen->nama_dept }} | {{ $result->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <div>
                                    <label for="basiInput" class="form-label fs-15">Disetujui oleh <i class="text-danger">*</i></label>
                                    <select class="form-control disetujui" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="disetujui[]" multiple>
                                        <option value="">Pilih</option>
                                        @foreach($disetujui as $result)
                                            @if($result->jabatan->id == '3' || $result->jabatan->id == '8' || $result->jabatan->id == '11' ) {{-- branch head, owner, direktur --}}
                                            <option value="{{ $result->nik }}">{{ $result->jabatan->nama_jabatan }} | {{ $result->nama }}</option>
                                            @else
                                            <option value="{{ $result->nik }}">{{ $result->jabatan->nama_jabatan }} {{ $result->departemen->nama_dept }} | {{ $result->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3">
                                <button class="btn btn-success mt-1 mb-1 simpanbuttonho" type="submit" id="simpanbuttonho">
                                    <i class="ri-save-3-fill align-bottom me-1 "></i> <strong>SIMPAN PERUBAHAN
                                    </strong>
                                </button>
                            </div>
                        </div>
                        
                    </form>
                    @endif
                    {{-- rb bisa dibatalkan kapanpun oleh admin ho --}}
                    {{-- Jika STATUS sudah ditolak dan sudah selesai tdk ditampilkan --}}
                    @if($data->STATUS != 0 && $data->STATUS != 5 && $data->STATUS != 6)
                    <div class="text-center mt-3">
                        <a href="javascript:void(0)" class="tolakadminho" id="tolakadminho"
                        data-v-cd5f1dea="" data-id="{{ $data->RB_ID  }}" data-user="{{ $user->nik }}">
                        <button type="button" class="btn btn-danger text-center mt-1 mb-1 tolakbuttonho" >
                            <i class="ri-close-fill align-bottom me-1 "></i>
                            <strong>TOLAK PENGAJUAN</strong>
                        </button>
                        </a>
                    </div>
                    @endif
                    @endif
                    {{-- end jika user admin rb --}}

                    {{-- jika user approval --}}
                    @if($data->STATUS == 3 || $data->STATUS == 4)
                    @if($user->AuthUsergrup->groupId == 1 || $cek != null)
                    
                    <div class="text-center mb-2">
                        <form method="POST" action="{{ route('persetujuan.update') }}">
                            @csrf
                            <input type="hidden" name="id_user" value="{{ $user->nik }}">
                            <input type="hidden" name="RB_ID" value="{{ $data->RB_ID }}">
                            <input type="hidden" name="STATUS" value="4">
                            
                            {{-- <button class="btn btn-success text-center" type="submit" id="btnsetujumnj">
                                <i class="ri-check-double-line align-bottom me-1 "></i>
                                <strong>SETUJUI</strong>
                            </button>
                            <button id="loadingmnj" style="display:none;" type="button" class="btn btn-success btn-load mt-1 mb-1" disabled>
                                <span class="d-flex align-items-center">
                                    <span class="spinner-border flex-shrink-0" role="STATUS">
                                        <span class="visually-hidden">Loading...</span>
                                    </span>
                                    <span class="flex-grow-1 ms-2">
                                        Loading...
                                    </span>
                                </span>
                            </button> --}}
                            <a href="javascript:void(0)" class="setujui" id="setujui"
                            data-v-cd5f1dea="" data-id="{{ $data->RB_ID  }}" data-user="{{ $user->nik }}" data-status="4">
                            <button type="button" class="btn btn-primary text-center mt-1 mb-1" >
                                <i class="ri-check-double-line align-bottom me-1 "></i>
                                <strong>SETUJUI</strong>

                            </button>
                            </a>
                            <a href="javascript:void(0)" class="tolak" id="tolak"
                            data-v-cd5f1dea="" data-id="{{ $data->RB_ID  }}" data-user="{{ $user->nik }}">
                            <button type="button" class="btn btn-danger text-center mt-1 mb-1">
                                <i class="ri-close-fill align-bottom me-1 "></i>
                                <strong>TOLAK</strong>

                            </button>
                            </a>
                            @if($cek->flag == 'MENYETUJUI')
                            <a href="javascript:void(0)" class="koreksi" id="koreksi"
                            data-v-cd5f1dea="" data-id="{{ $data->RB_ID  }}" data-user="{{ $user->nik }}">
                            <button type="button" class="btn btn-warning text-center mt-1 mb-1" >
                                <i class="ri-pencil-line align-bottom me-1 "></i>
                                <strong>KOREKSI</strong>

                            </button>
                            </a>
                            @endif
                        </form>
                    </div>
                    @endif
                    @endif

                    {{-- Otorisasi grup otorisasi--}}
                    @if($data->STATUS == 6)
                    @if($cekfinance == true)
                    <div>

                        <form method="POST" action="{{ route('persetujuan.update') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_user" value="{{ $user->nik }}">
                            <input type="hidden" name="RB_ID" value="{{ $data->RB_ID }}">
                            <input type="hidden" name="STATUS" value="0">
                            <div class="row">
                                <div class="col-xl-10">
                                    <label for="exampleFormControlTextarea5" 
                                        class="form-label mb-1">Lampirkan bukti transfer</label>
                                    <input name="buktitf" type="file" 
                                        class="form-control" accept=".pdf,.png,.jpg">
                                    <div id="passwordHelpBlock" class="form-text">
                                        Extenstion yang diizinkan untuk diupload: .pdf, .jpg, .png.
                                    </div>
                                </div>
                                <div class="col-xl-2 pt-4 text-center">
                                    
                                    <button class="btn btn-primary text-center " type="submit">
                                        <i class="ri-save-3-fill align-bottom me-1 "></i> <strong>Submit
                                    </strong>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                    @endif
                    {{-- stop --}}

                </div>
                
                
                <!-- end card body -->
            </div>
            <!--end card-->
        </div>
        <!--end col-->

    </div>
    <!--end row-->
@endsection
@push('before-scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
@endpush
@push('center-scripts')
<script src="{{ asset('assets/libs/list.js/prismjs.min.js') }}"></script>
<script src="{{ asset('assets/libs/list.js/list.js.min.js') }}"></script>
<script src="{{ asset('assets/libs/list.js/list.pagination.js.min.js') }}"></script>
<script src="{{ asset('assets/libs/list.js/listjs.init.js') }}"></script>
<script src="{{ asset('assets/libs/list.js/list.min.js') }}"></script>
<script src="{{ asset('assets/scripts/choices.min.js') }}"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(".confirm-delete").click(function(e) {
        // console.log(e.target.dataset.id);
        id = e.target.dataset.id;
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="mt-4 pt-2 fs-15 mx-5">' + '<h4>Anda yakin ?</h4>' +
                '<p class="text-muted mx-4 mb-0">Data Akan terhapus permanen !</p>' + '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {
            if (result.value) {
                // Swal.fire({
                //     title: 'Terhapus !',
                //     text: 'Data berhasil dihapus.',
                //     icon: 'success',
                //     confirmButtonClass: 'btn btn-primary w-xs mt-2',
                //     buttonsStyling: false
                // });
                $(`#delete-${id}`).submit();
            }
        });
    })
</script>
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            $("#pilihschema0").select2({
                theme: "bootstrap",
                placeholder: "Schema"
            });
        });
        $(document).ready(function () {
            $("#pilihcabang0").select2({
                theme: "bootstrap",
                placeholder: "Pilih Cabang"
            });
        });
        $(document).ready(function () {
            $("#pilihgrup0").select2({
                theme: "bootstrap",
                placeholder: "Grup"
            });
        });
        $(document).ready(function () {
            $("#pilihakunting0").select2({
                theme: "bootstrap",
                placeholder: "Pilih User"
            });
        });
    </script>
    <script>
        function getGrup(no){
            $("#pilihgrup"+no).html('');
            $("#pilihcabang"+no).html('');
            $("#pilihakunting"+no).html('');
            $('#pilihgrup'+no).html('<option value="">Grup</option>');
            $("#pilihgrup"+no).append('<option value="Dealer">Dealer</option>');
            $("#pilihgrup"+no).append('<option value="Ahass">Ahass</option>');
        }

        function getCabang(no){
            var schema_name = $('#pilihschema'+no).val();
            var grup = $('#pilihgrup'+no).val();
            console.log(schema_name, grup);
            $('#listloading1'+no).removeAttr('hidden');
            $('#pilihcabanghide'+no).attr('hidden','hidden');
            $.ajax({
                url: "{{url('email/getcabang')}}",
                type: "POST",
                data: {
                    schema_name: schema_name,
                    grup: grup,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $("#pilihcabang"+no).html('');
                    $('#pilihcabang'+no).html('<option value="">Pilih Cabang</option>');
                    console.log(result);
                    $.each(result, function (key, value) {
                        $("#pilihcabang"+no).append('<option value="' + value
                            .branch_id + '">' + value.branch_name +'</option>');
                    });
                    $('#pilihcabanghide'+no).removeAttr('hidden');
                    $('#listloading1'+no).attr('hidden','hidden');
                }
            });
        }
        function getUser(i){
            var schema_name = $('#pilihschema'+i).val();
            var grup = $('#pilihgrup'+i).val();
            var cabang = $('#pilihcabang'+i).val();
            
            $('#listloading2'+i).removeAttr('hidden');
            $('#pilihakuntinghide'+i).attr('hidden','hidden');
            $.ajax({
                url: "{{url('email/getemail')}}",
                type: "POST",
                data: {
                    schema_name: schema_name,
                    grup: grup,
                    cabang: cabang,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    $("#pilihakunting"+i).html('');
                    $('#pilihakunting'+i).html('<option value="">Pilih User Akuntansi</option>');
                    console.log(result);
                    $.each(result, function (key, value) {
                        $("#pilihakunting"+i).append('<option value="' + value
                            .phone_no + '">' + value.nama +'</option>');
                    });
                    $('#pilihakuntinghide'+i).removeAttr('hidden');
                    $('#listloading2'+i).attr('hidden','hidden');
                }
            });
        }
        function removeRow(no){
            $("#row"+no).remove();
        }
    </script>
    <script>
        var i = 0;

        $("#add").click(function () {
            ++i;
            console.log(i);
            $("#tambahcc").append(
                '<div class="row mt-2" id="row'+ i +'"><div class="col-sm-2"><select class="form-control" name="addmore[' +
                i +
                '][schemaname]" id="pilihschema' +
                i +
                '" onchange="getGrup('+ i +')" required><option value="">Schema</option><option value="MITRA">MITRA</option><option value="SEHATI">SEHATI</option><option value="JAYA">JAYA</option><option value="MAJU">MAJU</option></select></div><div class="col-sm-2"><select class="form-control" name="addmore[' +
                i +
                '][grup]" id="pilihgrup' +
                i +
                '" onchange="getCabang('+ i +')" required><option value="">Grup</option></select></div><div class="col-sm-5"><div id="pilihcabanghide' +
                i +
                '"><select class="form-control" name="addmore[' +
                i +
                '][cabang]" id="pilihcabang' +
                i +
                '" onchange="getUser('+ i +')" required><option value="">Pilih Cabang</option></select></div><div class="ph-item big p-1 mb-0" id="listloading1' +
                i +
                '" hidden><div class="ph-col-12 p-1 mb-0"><div class="ph-row"><div class="ph-col-12 big ph-border-1"></div></div></div></div></div><div class="col-sm-2"><div id="pilihakuntinghide' +
                i +
                '"><select class="form-control" name="addmore[' +
                i +
                '][phone_no]" id="pilihakunting' +
                i +
                '" required><option value="">Pilih</option></select></div><div class="ph-item big p-1 mb-0" id="listloading2' +
                i +
                '" hidden><div class="ph-col-12 p-1 mb-0"><div class="ph-row"><div class="ph-col-12 big ph-border-1"></div></div></div></div></div><div class="col-sm-1"><button type="button" onclick="removeRow('+ i +')" class="btn btn-danger btn-sm mt-1 remove-row"><i class="fa fa-trash"></i></button></div></div>'
            );
            
            $('#pilihschema'+i).select2({
                theme: "bootstrap",
                placeholder: "Schema"
            });
            $("#pilihcabang"+i).select2({
                theme: "bootstrap",
                placeholder: "Pilih Cabang"
            });
            $("#pilihgrup"+i).select2({
                theme: "bootstrap",
                placeholder: "Grup"
            });
            $("#pilihakunting"+i).select2({
                theme: "bootstrap",
                placeholder: "Pilih User"
            });
        });

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.tolak', function() {
                console.log($(this).data('user'));
                console.log($(this).data('id'));
                var id = $(this).data('id');
                var user = $(this).data('user');
                $("#id").empty();
                $("#user").empty();
                $("#alasantolak").val('');
                $('.id').val(id);
                $('.user').val(user);
                $('#modaltolak').modal('show');
            });
        });
    </script>
    <script type="text/javascript">   
        $(document).ready(function () {
            $(document).on('click', '.setujui', function() {
                console.log($(this).data('id'));
                var id = $(this).data('id');
                var user = $(this).data('user');
                var STATUS = $(this).data('status');
                $("#id").empty();
                $("#user").empty();
                $("#STATUS").empty();
                $('#id').val(id);
                $('#user').val(user);
                $('#STATUS').val(STATUS);
                $("#alasansetuju").val('');
                $('#modalsetuju').modal('show');
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.tolakadminho', function() {
                console.log($(this).data('id'));
                var id = $(this).data('id');
                var user = $(this).data('user');
                $("#idtlk").empty();
                $("#usertlk").empty();
                $('#idtlk').val(id);
                $('#usertlk').val(user);
                $("#alasantolaktlk").val('');
                $('#modaltolakadminho').modal('show');
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on('click', '.koreksi', function() {
                console.log($(this).data('id'));
                var id = $(this).data('id');
                var user = $(this).data('user');
                $('#idrb').val(id);
                $('#userkoreksi').val(user);
                $('#modalkoreksi').modal('show');
            });
        });
    </script>
    @if (session('message'))
        <script>
            Toastify({
                text: "{{ session('message') }}",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(to right, #0ab39c, #2982aa)",
                },
                //onClick: function(){} // Callback after click
            }).showToast();
        </script>
    @endif
    @if($errors->any())
        <script>
            alert("Kolom Diketahui/ Mengetahui tidak boleh kosong !");
        </script>
    @endif
    <script type="text/javascript">
        $('#btnsetujuikcb').click(function() {
            $(this).css('display', 'none');
            $('#loadingkcb').show();
            return true;
        });
    </script>
    <script type="text/javascript">
        $('#btnsetujumnj').click(function() {
            $(this).css('display', 'none');
            $('#loadingmnj').show();
            return true;
        });
    </script>
@endpush
