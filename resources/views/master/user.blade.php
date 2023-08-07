@extends('layouts.master')
@section('title', 'Authentication User')
@section('nav_active_user', 'active')
@section('nav_active_master_muser', 'active')
@section('content')


    <div class="modal fade zoomIn" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel"
        data-v-01bddeea="" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalLabel" data-v-01bddeea=""> Tambah User </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.simpan') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('nik') text-danger @enderror">Karyawan <i class="text-danger fs-15">*</i></label>
                                    <select class="form-control mb-0" data-choices name="nik" id="choices-single-default">
                                        <option value="">Pilih Karyawan</option>
                                        @foreach($karyawan as $kry)
                                            <option value="{{ $kry->nik }}" @if(old('nik') == $kry->nik) selected @endif>{{ $kry->cabang->branch_name }} | {{ $kry->jabatan->nama_jabatan }} {{ $kry->departemen->nama_dept }}  | {{ $kry->nama }}</option>
                                        @endforeach
                                    </select>
                                     
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('email') text-danger @enderror">Email <i class="text-danger fs-15">*</i></label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror" id="validationCustom04"
                                        autocomplete="off">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label @error('password') text-danger @enderror">Password <i class="text-danger fs-15">*</i></label>
                                        <input type="password" name="password" 
                                        class="form-control @error('password') is-invalid @enderror" id="validationCustom04"
                                        autocomplete="off">
                                  
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label @error('izin') text-danger @enderror">Autentikasi Pengguna</label>
                                        <select multiple="multiple" name="izin[]" id="multiselect-optiongroup">
                                            <optgroup label="Autentikasi">
                                                <option value="3">Pengguna</option>
                                                <option value="6">Grup</option>
                                            </optgroup>
                                            <optgroup label="App Budgeting">
                                                <option value="7">Pengajuan</option>
                                                <option value="8">Persetujuan</option>
                                                <option value="9">Pengesahan</option>
                                                <option value="10">Otorisasi</option>
                                            </optgroup>
                                            <optgroup label="App Inventaris">
                                                <option value="11">Data Inventaris</option>
                                                <option value="12">Query Inventaris</option>
                                                <option value="13">Peminjaman</option>
                                                <option value="14">Pemeliharaan</option>
                                                <option value="18">Query RB</option>
                                            </optgroup>
                                            <optgroup label="Master Data">
                                                <option value="2">Karyawan</option>
                                                <option value="4">Departemen</option>
                                                <option value="5">Jabatan</option>
                                                <option value="1">Cabang</option>
                                                <option value="15">Jenis Inventaris</option>
                                                <option value="16">Grup Inventaris</option>
                                            </optgroup>
                                        </select>
                                  
                                    @error('izin')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('level') text-danger @enderror">Autentikasi Grup <i class="text-danger fs-15">*</i></label>
                                    <select class="form-control" data-choices name="level" id="choices-single-default">
                                        <option value="">Pilih Grup Autentikasi</option>
                                        @foreach($grup as $rslt)
                                        <option value="{{ $rslt->id }}" @if(old('level') == $rslt->id) selected @endif>{{ $rslt->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                                    <div id="loading" style="display:none;">
                                        <button type="button" class="btn btn-primary btn-load" disabled>
                                            <span class="d-flex align-items-center">
                                                <span class="spinner-border flex-shrink-0" role="status">
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

    <div class="modal fade zoomIn" id="modaledit" tabindex="-1" aria-labelledby="exampleModalgridLabel" data-v-01bddeea=""
        style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-primary" data-v-01bddeea="">
                    <h5 class="modal-title text-light" id="exampleModalgridLabel">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal" data-v-01bddeea=""></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                        <div class="row g-3">

                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom02"
                                        class="form-label @error('password') text-danger @enderror">Edit Password</label>
                                        <input type="password" name="password" 
                                        class="form-control @error('password') is-invalid @enderror" id="validationCustom04"
                                        autocomplete="off">
                                  
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xxl-12">
                                <div>
                                    <label for="validationCustom04"
                                        class="form-label @error('edit_email') text-danger @enderror">Email</label>
                                    <input type="email" name="edit_email" id="edit_email" value="{{ old('edit_email') }}"
                                        class="form-control @error('edit_email') is-invalid @enderror" id="validationCustom04"
                                        autocomplete="off">
                                    @error('edit_email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" id="btnSubmitedit" class="btn btn-primary">Submit</button>
                                    <div id="loadingedit" style="display:none;">
                                        <button type="button" class="btn btn-primary btn-load" disabled>
                                            <span class="d-flex align-items-center">
                                                <span class="spinner-border flex-shrink-0" role="status">
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
        <div class="col-xl-12">
            <div div class="card">
                <div class="card-header" >
                    <h6 class="card-title mb-0 font-poppins fs-15" >Autentikasi Pengguna</h6>
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
                            <div class="d-flex justify-content-md-end justify-content-center" data-v-cd5f1dea="">
                                <div class="search-box ms-2" data-v-cd5f1dea=""><input type="text"
                                        class="form-control" placeholder="Search .." data-v-cd5f1dea="" id="myInput"
                                        name="search"><i class="ri-search-line search-icon" data-v-cd5f1dea=""></i>
                                </div>
                            </div>
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
                                    <th class="sort" data-sort="email">Name</th>
                                    <th class="sort" data-sort="phone">Username</th>
                                    <th class="sort" data-sort="date">Email</th>
                                    <th class="sort" data-sort="status">Cabang</th>
                                    <th class="sort" data-sort="date">Grup Autentikasi</th>
                                    <th class="sort" data-sort="date">Status</th>
                                    <th class="sort" data-sort="status">Updated at</th>
                                    <th class="sort" data-sort="action">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all" id="carirow">
                                @foreach ($data as $no => $item)
                                    <tr>
                                        <td class="status">{{ $data->firstItem() + $no }}</td>
                                        <td class="customer_name">{{ $item->name }}</td>
                                        <td class="email"> {{ $item->username }}</td>
                                        <td class="email">{{ $item->email }}</td>
                                        <td class="phone">{{ $item->karyawan->cabang->branch_name }}</td>
                                        <td class="email">{{ $item->ugrup->grup->name }}</td>
                                        <td class="email">
                                            <div>
                                                <div class="form-check form-switch" id='editstatus'>
                                                    <input class="form-check-input" data-id="{{ $item->username }}" data-status="{{ $item->status }}" type="checkbox" role="switch" id="SwitchCheck7" name="status" @if($item->status == '1') checked @endif>
                                                </div>
                                                <div id="showloading{{ $item->username }}" style="display:none;">
                                                    <span class="spinner-border spinner-border-sm flex-shrink-0"> </span>
            
                                                </div>
                                            </div>
                                            {{-- @if($item->status == 1)
                                            <span class="badge badge-soft-info badge-border fs-12 text-wrap">
                                                Aktif
                                            </span>
                                            @else
                                            <span class="badge badge-soft-danger badge-border fs-12 text-wrap">
                                                Non aktif 
                                            </span>
                                            @endif --}}
                                        </td>
                                        <td class="date">@tanggal($item->updated_at)</td>
                                        <td>
                                            <span data-v-cd5f1dea="">
                                                <div class="dropdown" data-v-cd5f1dea=""><button
                                                        class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        data-v-cd5f1dea=""><i class="ri-more-fill"
                                                            data-v-cd5f1dea=""></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end" data-v-cd5f1dea=""
                                                        style="">

                                                        <li data-v-cd5f1dea="">
                                                            <a href="javascript:void(0)" class="dropdown-item edit"
                                                                id="edit" data-v-cd5f1dea=""
                                                                data-id="{{ $item->username }}"><i
                                                                    class="ri-pencil-fill align-bottom me-2 text-muted"
                                                                    data-v-cd5f1dea=""></i> Edit</a>
                                                        </li>
                                                        <li data-v-cd5f1dea="">
                                                            <a class="dropdown-item remove-item-btn confirm-delete"
                                                                data-v-cd5f1dea="" data-id="{{ $item->username }}">
                                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"
                                                                    data-v-cd5f1dea="">
                                                                </i>
                                                                Delete
                                                                <form method="POST"
                                                                    action="{{ route('user.hapus', $item->username) }}"
                                                                    id="delete-{{ $item->username }}">
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
                        {{-- <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0"><li class="active"><a class="page" href="#" data-i="1" data-page="8">1</a></li><li><a class="page" href="#" data-i="2" data-page="8">2</a></li></ul>
                                <a class="page-item pagination-next" href="#">
                                    Next
                                </a>
                            </div> --}}
                        {{ $data->links() }}
                    </div>
                </div>

            </div>
        </div>
        {{-- <div class="col-xl-3 col-md-3">
            <div class="card"  data-v-37db4fe8="">
                <div class="card-header" >
                    <h6 class="card-title mb-0 font-poppins fs-15" >Related Autentikasi</h6>
                </div>
                <div class="card-body" >
                    <div class="list-setting" >
                        <div class="d-flex mb-2 list-setting_item pointer" >
                            <div class="flex-shrink-0" ><span
                                    class="badge badge-soft-primary p-1 fs-15" ><i
                                        class="text-info ri-user-settings-line" ></i></span></div>
                            <div class="flex-grow-1 ms-2 mt-0 mt-1 overflow-hidden" ><a
                                    href="{{ route('user')}}" class="" >
                                    <h6 class="text-info text-truncate fs-13 mb-0 font-poppins" >
                                        Pengguna</h6>
                                </a></div>
                        </div>
                        <div class="d-flex mb-2 list-setting_item pointer" >
                            <div class="flex-shrink-0" ><span
                                    class="badge badge-soft-primary p-1 fs-15" ><i
                                        class="ri-group-line" ></i></span></div>
                            <div class="flex-grow-1 ms-2 mt-0 mt-1 overflow-hidden" ><a
                                    href="{{ route('grup')}}" class="" >
                                    <h6 class=" text-truncate fs-13 mb-0 font-poppins" >
                                        Grup</h6>
                                </a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
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
    <!-- multi.js -->
    <script src="{{ asset('assets/libs/multi.js/multi.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced2.init.js') }}"></script>

    {{-- <script src="{{ asset('assets/libs/sweetalert2.min.js') }}"></script> --}}
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
        $(document).ready(function() {
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#carirow tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    <script>
        $("input:checkbox").on("change", function () {
            
            console.log($(this).data('id'));
            console.log($(this).data('status'));
            var id = $(this).data('id');
            var status = $(this).data('status');
            var _token = "{{ csrf_token() }}";
            
            $(this).css('display', 'none');
            $('#showloading'+id+'').show();

            $.ajax({type: "POST",
                url     : 'user/update/status',
                data    : {
                    _token:_token,
                    id: id,
                    status:status
                },
                success :  function(){
                    
                    Toastify({
                        text: "Status berhasil diupdate!",
                        duration: 4000,
                        close: true,
                        gravity: "top", // `top` or `bottom`
                        position: "right", // `left`, `center` or `right`
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                        style: {
                            background: "linear-gradient(to right, #0ab39c, #2982aa)",
                        },
                        //onClick: function(){} // Callback after click
                    }).showToast();
                    window.location.reload();
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.edit', function() {
            var url = "user/";
            var id = $(this).data('id');
            var statusSelect = $('#level');
            $.get(url + id + '/edit', function(data) {
                //success data
                console.log(data);
                $('#id').val(data.username);
                $('#edit_email').val(data.email);
                $('#status_user').val(data.role);
                statusSelect.append(data.role).trigger('change'); // Add this line
                $('#modaledit').modal('show');
            })
        });
    </script>
    <script>
        @if ($errors->has('nama') || $errors->has('telp') || $errors->has('jk') || $errors->has('bagdept') || $errors->has('password') ||$errors->has('level'))
            $(document).ready(function() {

                $('#exampleModalgrid').modal('show');

            });
        @endif

        @if ($errors->has('edit_email') ||
            $errors->has('edit_level'))

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
