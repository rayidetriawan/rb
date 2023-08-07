@extends('layouts.master')
@section('title', 'Master Data Kategori Masalah')
@section('nav_active_kategori_masalah', 'active')
@section('nav_active_msupporttkt', 'active')
@section('nav_active_master_data', 'active')
@section('content')

   
    <div class="modal fade zoomIn" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel" data-v-01bddeea=""
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalLabel" data-v-01bddeea=""> Tambah Data </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kategorimasalah.simpan') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-xl-6">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('jenmasalah') text-danger @enderror">Jenis Masalah <i class="text-danger">*</i></label>
                                    
                                    <select data-width="100%" class="form-control" name="jenmasalah" id="jenmasalah" required>
                                        <option value="">Pilih</option>
                                        <option value="software">Software</option>
                                        <option value="hardware">Hardware</option>
                                    </select>
                                    
                                    @error('jenmasalah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('bisnis') text-danger @enderror">Bisnis <i class="text-danger">*</i></label>
                                    <select data-width="100%" class="form-control" name="bisnis" id="bisnis" required>
                                        <option value="">Pilih</option>
                                        <option value="dealer">Dealer</option>
                                        <option value="bengkel">Bengkel</option>
                                    </select>
                                    @error('bisnis')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('subjenmasalah') text-danger @enderror">Sub Jenis Masalah <i class="text-danger">*</i></label>
                                        <select data-width="100%" class="form-control" name="subjenmasalah" id="subjenmasalah" required>
                                            <option value="">Pilih</option>
                                        </select>
                                    @error('subjenmasalah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('katmasalah') text-danger @enderror">Kategori Masalah <i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="katmasalah" id="katmasalah">
                                    @error('katmasalah')
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

    <div class="modal fade zoomIn" id="modaledit" tabindex="-1" aria-labelledby="exampleModalgridLabel" data-v-01bddeea=""
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalgridLabel">Edit Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('bank.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                        <div class="row g-3">
                            
                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('bank') text-danger @enderror">Bank <i class="text-danger">*</i></label>
                                    
                                    <select data-width="100%" class="form-control" name="bank" id="pilihbank2" required>
                                        <option value="">Pilih Bank</option>
                                        
                                    </select>
                                    
                                    @error('bank')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('nama') text-danger @enderror">Nama akun Bank <i class="text-danger">*</i></label>
                                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                                        class="form-control @error('nama') is-invalid @enderror" id="validationCustom04"
                                        autocomplete="off" required>
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('norek') text-danger @enderror">No Rekening <i class="text-danger">*</i></label>
                                    <input type="text" id="norek" name="norek" value="{{ old('norek') }}"
                                        class="form-control @error('norek') is-invalid @enderror" id="validationCustom04"
                                        autocomplete="off" required>
                                    @error('norek')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-xl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label @error('status') text-danger @enderror">Status rekening <i class="text-danger">*</i></label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" value="1"
                                                id="inlineRadio1" @if(old('status') == '1') checked @endif>
                                            <label class="form-check-label" for="inlineRadio1">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" value="0"
                                                id="inlineRadio2" @if(old('status') == '0') checked @endif>
                                            <label class="form-check-label" for="inlineRadio2">Non aktif</label>
                                        </div>
                                    </div>
                                    @error('status')
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
        <div class="col-xl-12">
            <div div class="card">
                <div class="card-header" >
                    <h6 class="card-title mb-0 font-poppins fs-15" >Master Data Kategori Masalah</h6>
                </div>
                <div class="card-header border-0" data-v-cd5f1dea="">
                    <div class="row g-4" data-v-cd5f1dea="">
                        <div class="col-12 col-md-6" data-v-cd5f1dea="">
                          
                                <div class="d-flex justify-content-md-start justify-content-center" data-v-cd5f1dea=""
                                    data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                                    {{-- <a href="{{ route('tambah') }}"> --}}
                                    <button class="btn btn-outline-secondary" data-v-cd5f1dea="">
                                        <i class="ri-add-line align-bottom me-1" data-v-cd5f1dea=""></i> Tambah Data
                                    </button>
                                    {{-- </a> --}}
                                </div>
                          

                        </div>
                        <div class="col-12 col-md-6" data-v-cd5f1dea="">
                            {{-- <form action="{{ route('bank.cari') }}" method="get">
                            <div class="d-flex justify-content-md-end justify-content-center" data-v-cd5f1dea="">
                                <div class="search-box ms-2" data-v-cd5f1dea=""><input type="text"
                                        class="form-control" value="{{ old('search') }}" placeholder="Cari bank, rekening, nama akun bank.." data-v-cd5f1dea=""
                                        name="search"><i class="ri-search-line search-icon" data-v-cd5f1dea=""></i>
                                </div>
                            </div>
                            </form> --}}
                        </div>
                    </div>
                </div>
                <!-- end card header -->
                <div class="card-body">

                    <div class="table-responsive table-card mb-1">
                        <table class="table align-middle table-nowrap" id="customerTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="sort" data-sort="customer_name">No</th>
                                    <th class="sort" data-sort="email">Jenis Masalah</th>
                                    <th class="sort" data-sort="phone">Bisnis</th>
                                    <th class="sort" data-sort="date">Sub Jenis Masalah</th>
                                    <th class="sort" data-sort="date">Kategori Masalah</th>
                                    <th class="sort" data-sort="date">Last User</th>
                                    <th class="sort" data-sort="date">Last Update</th>
                                    <th class="sort" data-sort="action">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all" id="carirow">
                                @if(count($data) > 0)
                                    @foreach ($data as $no => $item)
                                        <tr>
                                            <td class="status align-top">{{ $data->firstItem() + $no }}</td>
                                            <td class="customer_name align-top">{{ $item->jenis_masalah }}</td>
                                            <td class="email align-top"> {{ $item->bisnis }}</td>
                                            <td class="email align-top">{{ $item->sub_jenis_masalah }}</td>
                                            <td class="email align-top">{{ $item->kategori_masalah }}</td>
                                            <td class="email align-top">{{ $item->last_user_karyawan->nama }}</td>
                                            <td class="date align-top">@tanggal($item->updated_date)</td>
                                            <td class="align-top">
                                                <span data-v-cd5f1dea="">
                                                    <div class="dropdown" data-v-cd5f1dea=""><button
                                                            class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false"
                                                            data-v-cd5f1dea=""><i class="ri-more-fill"
                                                                data-v-cd5f1dea=""></i></button>
                                                        <ul class="dropdown-menu dropdown-menu-end" data-v-cd5f1dea=""
                                                            style="">

                                                            {{-- <li data-v-cd5f1dea="">
                                                                <a href="javascript:void(0)" class="dropdown-item edit"
                                                                    id="edit" data-v-cd5f1dea=""
                                                                    data-id="{{ $item->id }}"><i
                                                                        class="ri-pencil-fill align-bottom me-2 text-muted"
                                                                        data-v-cd5f1dea=""></i> Edit</a>
                                                            </li> --}}
                                                            <li data-v-cd5f1dea="">
                                                                <a class="dropdown-item remove-item-btn confirm-delete"
                                                                    data-v-cd5f1dea="" data-id="{{ $item->id }}">
                                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"
                                                                        data-v-cd5f1dea="">
                                                                    </i>
                                                                    Delete
                                                                    <form method="POST"
                                                                        action="{{ route('kategorimasalah.hapus', $item->id) }}"
                                                                        id="delete-{{ $item->id }}">
                                                                        @csrf
                                                                        @method('delete')
                                                                    </form>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Data tidak ditemukan</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any
                                    orders for you search.</p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $data->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

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

    {{-- <script src="{{ asset('assets/libs/sweetalert2.min.js') }}"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(".confirm-delete").click(function(e) {
            // console.log(e.target.dataset.id);
            id = e.target.dataset.id;
            Swal.fire({
                html: '<div class="mt-3">' +
                    '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" style="width:100px;height:100px"></lord-icon>' +
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
        $('#exampleModalgrid').on('shown.bs.modal', function () {
            $("#jenmasalah").select2({
                theme: "bootstrap",
                placeholder: "Pilih",
                dropdownParent: "#exampleModalgrid"
            });
            $("#bisnis").select2({
                theme: "bootstrap",
                placeholder: "Pilih",
                dropdownParent: "#exampleModalgrid"
            });
            $("#subjenmasalah").select2({
                theme: "bootstrap",
                placeholder: "Pilih",
                dropdownParent: "#exampleModalgrid"
            });
        });
        // $(document).ready(function () {
        //     $("#jenmasalah").select2({
        //         theme: "bootstrap",
        //         placeholder: "Pilih",
        //         dropdownParent: "#exampleModalgrid"
        //     });
        //     $("#bisnis").select2({
        //         theme: "bootstrap",
        //         placeholder: "Pilih",
        //         dropdownParent: "#exampleModalgrid"
        //     });
        // });
    </script>
    <script>
        $(document).ready(function() {

            $('#jenmasalah').on('change', function() {
                var jenis = this.value;
                if (jenis === 'hardware') {
                    $('#bisnis').prop("disabled", true);
                    $('#bisnis').val($('#bisnis option:first').val()).trigger('change');
                    $("#subjenmasalah").empty();
                    $("#subjenmasalah").append('<option value="Komputer">Komputer</option>');
                    $("#subjenmasalah").append('<option value="CCTV">CCTV</option>');
                    $("#subjenmasalah").append('<option value="Internet">Internet</option>');
                    $("#subjenmasalah").append('<option value="Internet">Printer</option>');
                }else{
                    $('#bisnis').prop("disabled", false);
                    $('#bisnis').val($('#bisnis option:first').val()).trigger('change');
                    $("#subjenmasalah").empty();
                    $("#subjenmasalah").append('<option value="Dealer System">Dealer System</option>');
                    $("#subjenmasalah").append('<option value="Bengkel System">Bengkel System</option>');
                    $("#subjenmasalah").append('<option value="KPI System">KPI System</option>');
                }
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
    @if (session('messageWarning'))
        <script>
            Toastify({
                text: "{{ session('messageWarning') }}",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                    background: "linear-gradient(to right, #621219, #a40a0a)",
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
