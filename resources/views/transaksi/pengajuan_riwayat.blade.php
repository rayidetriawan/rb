@extends('layouts.master')
@section('title', 'Riwayat Pengajuan')
@section('nav_active_rb_head', 'active')
@section('nav_active_pengajuan', 'active')
@section('nav_active_pengajuan_list', 'active')
@section('content')

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header bg-light">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filter Data</h5>
            {{-- <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button> --}}
        </div>
        <!--end offcanvas-header-->
        <form class="d-flex flex-column justify-content-end h-100">
            <div class="offcanvas-body">
                {{-- <div class="mb-4">
                    <label for="fcabang" class="form-label text-muted text-uppercase fw-semibold mb-3">Cabang</label>
                    <select class="form-control" id="fcabang" name="cabang">
                        <option value="">Pilih cabang</option>
                        @foreach ($cabang as $cbg)
                            <option value="{{ $cbg->branch_id }}-{{ $cbg->schema_name }}">{{ $cbg->schema_name }} |
                                {{ $cbg->branch_name }}</option>
                        @endforeach

                    </select>
                </div> --}}
                <div class="mb-4">
                    <label for="country-select" class="form-label text-muted text-uppercase fw-semibold mb-3">Status
                        RB</label>
                    <select class="form-control fstatus" name="status" id="fstatus">
                        <option value="">Pilih status</option>
                        <option value="1">Menunggu Aproval Kacab/ Kabeng</option>
                        <option value="2">Proses Validasi Pengesahan</option>
                        <option value="3">Menunggu Approval Terkait</option>
                        <option value="4">Menunggu Approval Management Terkait</option>
                        <option value="5">Ditolak</option>
                        <option value="6">Menunggu otorisasi</option>
                        <option value="7">RB Harus Dikoreksi</option>
                        <option value="O">RB telah selesai</option>
                    </select>

                </div>
                <div class="mb-4">
                    <label for="status-select" class="form-label text-muted text-uppercase fw-semibold mb-3">Tanggal RB </label>
                    <div class="row g-2 align-items-center">
                        <div class="col-lg">
                            <input type="date" class="form-control" id="fromdate" name="fromdate">
                        </div>
                        <div class="col-lg-auto">
                            sampai
                        </div>
                        <div class="col-lg">
                            <input type="date" class="form-control" id="todate" name="todate">
                        </div>
                    </div>
                </div>
            </div>
            <!--end offcanvas-body-->
            <div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
                <button onclick="location.reload();" class="btn btn-danger w-100" data-bs-dismiss="offcanvas"
                    aria-label="Close" id="breset">Reset</button>
                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="offcanvas" id="bfilter">Filter</button>
            </div>
            <!--end offcanvas-footer-->
        </form>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="orderList">
                <div class="card-header  border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Riwayat Pengajuan</h5>
                        <div class="flex-shrink-0">
                            {{-- <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Create Order</button>
                            <button type="button" class="btn btn-info"><i class="ri-file-download-line align-bottom me-1"></i> Import</button>
                            <button class="btn btn-soft-danger" onclick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button> --}}
                        </div>
                    </div>
                </div>
                <div class="card-body border border-dashed border-end-0 border-start-0">
                    <form>
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <div class="search-box">
                                    <input type="text" class="form-control search"
                                        placeholder="Pencarian untuk Rb Id, Item dan total..." id="search" name="search" autocomplete="off" onkeydown="return (event.keyCode!=13);">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->

                            <div class="col-sm-auto ms-auto">
                                <div>
                                    {{-- <button type="button" class="btn btn-primary w-100"> <i
                                            class="ri-equalizer-fill me-1 align-bottom"></i>
                                        Filters
                                    </button> --}}
                                    <button type="button" class="btn btn-primary w-lg" data-bs-toggle="offcanvas"
                                        href="#offcanvasExample"><i class="ri-equalizer-fill align-bottom me-1"></i>
                                        Filter</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <div class="card-body pt-0">
                    <div>
                        <ul role="tablist">

                        </ul>

                        <div class="table-responsive table-card mb-1 mt-3">
                            <table class="table table-nowrap align-middle data-table" id="orderTable">
                                <thead class="text-muted table-light">
                                    <tr class="text-uppercase">
                                        <th>#</th>
                                        <th class="sort desc" data-sort="id">RB ID</th>
                                        <th class="sort" data-sort="amount">Item</th>
                                        <th class="sort" data-sort="status">Status</th>
                                        <th class="sort" data-sort="customer_name">Last User Update</th>
                                        <th class="sort" data-sort="customer_name">Created date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody class="list form-check-all" id="loadingtable" hidden>
                                    
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-0">
                                            <div class="ph-item big p-0 mb-0 border-0" id="listloading">
                                                <div class="ph-col-12 p-1 mb-0">
                                                    <div class="ph-row">
                                                        <div class="ph-col-12 big ph-border-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                                <tbody class="list form-check-all" id="showtable">
                                    @foreach ($data as $no => $hist)
                                        <tr>
                                            <td class="align-top">{{ $data->firstItem() + $no }}</td>
                                            <td class="id align-top">
                                                {{ $hist->RB_ID }}
                                            </td>
                                            <td class="align-top">
                                                <ul class="ps-2 vstack mb-1">
                                                    @foreach ($hist->TrxRbDetailItem as $item)
                                                        <li>{{ $item->ITEM }}
                                                            <div class="text-muted">
                                                               {{ $item->QTY }} pcs x @uang($item->HARGA) = @uang($item->QTY * $item->HARGA)
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                    <br>
                                                    Total = Rp. @uang($hist->TOTAL_HARGA)
                                                </ul>
                                            </td>
                                            <td class="align-top">
                                                @if ($hist->STATUS == 1)
                                                    <span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                                        Menunggu Aproval Kacab/ Kabeng
                                                    </span>
                                                @elseif($hist->STATUS == 2)
                                                    <span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                                        Proses Validasi Pengesahan
                                                    </span>
                                                @elseif($hist->STATUS == 3)
                                                    <span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                                        Menunggu Aproval Terkait
                                                    </span>
                                                @elseif($hist->STATUS == 4)
                                                    <span class="badge badge-soft-secondary badge-border fs-12 text-wrap">
                                                        Menunggu Approval Management Terkait
                                                    </span>
                                                @elseif($hist->STATUS == 5)
                                                    <span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                                        Ditolak
                                                    </span>
                                                @elseif($hist->STATUS == 6)
                                                    <span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                                        Menunggu otorisasi
                                                    </span>
                                                @elseif($hist->STATUS == 7)
                                                    <span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                                        RB Harus Dikoreksi
                                                    </span>
                                                @elseif($hist->STATUS == 0)
                                                    <span class="badge badge-soft-success badge-border fs-12 text-wrap">
                                                        RB telah selesai
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="align-top">
                                                {{ $hist->lastupdateby['nama'] }}
                                            </td>
                                            <td class="align-top">
                                                {{ $hist->CREATED_DATE }}
                                            </td>
                                            <td class="align-top text-center">
                                                <a href="{{ route('pengajuan.detail', $hist->RB_ID) }}"
                                                    class="btn btn-sm btn-light">
                                                    Detail

                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted">We've searched more than 150+ Orders We did not find any orders
                                        for you search.</p>
                                </div>
                            </div>
                        </div>
                        @if(!empty($data))
                        <div class="d-flex justify-content-end">
                            {{ $data->links() }}
                        </div>
                        @endif
                    </div>
                    <!--end modal -->
                </div>
            </div>

        </div>
    </div>

@endsection

@push('before-scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
@endpush

@push('center-scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets/libs/list.js/prismjs.min.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/listjs.init.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/list.min.js') }}"></script>

    {{-- <script src="{{ asset('assets/libs/sweetalert2.min.js') }}"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#fcabang").select2({
                theme: "bootstrap",
                placeholder: "Pilih cabang",
                dropdownParent: "#offcanvasExample"
            });
            $("#fstatus").select2({
                theme: "bootstrap",
                placeholder: "Pilih status",
                dropdownParent: "#offcanvasExample"
            });
        });
    </script>
    <script type="text/javascript">
        $('#search').on('keyup', function() {
            $value = $(this).val();
            $status = $('#fstatus').val();
            $fromdate = $('#fromdate').val();
            $todate = $('#todate').val();

            $('#loadingtable').removeAttr('hidden');
            $("#showtable").attr('hidden','hidden');
            $(".pagination").attr('hidden','hidden');
            $.ajax({
                type: 'get',
                url: '{{ URL::to('pengajuan/liveSearch') }}',
                data: {
                    'search': $value,
                    'status'    : $status,
                    'fromdate'  : $fromdate,
                    'todate'    : $todate
                },
                success: function(data) {
                    $('#showtable').removeAttr('hidden');
                    $("#loadingtable").attr('hidden','hidden');
                    $('#showtable').html(data);
                }
            });
        })
    </script>
    <script type="text/javascript">
        $('#breset').on('click', function() {
            $('#loadingtable').removeAttr('hidden');
            $("#showtable").attr('hidden','hidden');
        });
        $('#bfilter').on('click', function() {
            $status = $('#fstatus').val();
            $fromdate = $('#fromdate').val();
            $todate = $('#todate').val();
            $(".pagination").attr('hidden','hidden');

            $('#loadingtable').removeAttr('hidden');
            $("#showtable").attr('hidden','hidden');
            $.ajax({
                type: 'get',
                url: '{{ URL::to('pengajuan/liveSearch') }}',
                data: {
                    'status'    : $status,
                    'fromdate'  : $fromdate,
                    'todate'    : $todate
                },
                success: function(data) {
                    $('#showtable').removeAttr('hidden');
                    $("#loadingtable").attr('hidden','hidden');
                    $('#showtable').html(data);
                }
            });
        })
    </script>
    
    {{-- <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'csrftoken': '{{ csrf_token() }}'
            }
        });
    </script> --}}
    <script>
        @if ($errors->any())
            $(document).ready(function() {

                $('#exampleModalgrid').modal('show');

            });
        @endif

        @if ($errors->has('edit_nama'))

            $(document).ready(function() {

                $('#modaledit').modal('show');

            });
        @endif
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
    <script type="text/javascript">
        $('#btnSubmit').click(function() {
            $(this).css('display', 'none');
            $('#loading').show();
            return true;
        });
    </script>
    <script type="text/javascript">
        $('#btnSubmitedit').click(function() {
            $(this).css('display', 'none');
            $('#loadingedit').show();
            return true;
        });
    </script>
@endpush
