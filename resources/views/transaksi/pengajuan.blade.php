@extends('layouts.master')
@section('title', 'Pengajuan Budget')
@section('nav_active_rb_head', 'active')
@section('nav_active_pengajuan', 'active')
@section('nav_active_pengajuan_baru', 'active')
@section('content')


    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('pengajuan.simpan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pengajuan Budget</h5>
                    </div>
                    <div class="card-body mb-2">
                        <div class="row">
                            <div class="col-xl-4">
                                <div>
                                    <label for="validationCustom04" class="form-label mb-1">No. RB <i
                                            class="text-danger">*</i></label>
                                    <input type="text" name="no_rb" value="{{ $kodeRb }}" class="form-control"
                                        id="validationCustom04" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div>
                                    <label for="validationCustom04" class="form-label mb-1">Cabang <i
                                            class="text-danger">*</i></label>
                                    <input type="hidden" name="cabang" value="{{ $user->branch_id }}">
                                    <input type="text" value="{{ $user->cabang->branch_name }}" class="form-control"
                                        id="validationCustom04" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div>
                                    <label for="validationCustom04" class="form-label mb-1">Diajukan Oleh <i
                                            class="text-danger">*</i></label>
                                    <input type="hidden" name="nama" value="{{ $user->nik }}">
                                    <input type="text" value="{{ $user->nama }}" class="form-control"
                                        id="validationCustom04" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <div class="col-xl-4">

                                <div class="pt-2">
                                    <label for="exampleFormControlTextarea5" class="form-label mb-1">Dana ditransfer ke Bank
                                        <i class="text-danger">*</i></label>
                                    <select class="form-control" name="bank" id="pilihbank" required>
                                        <option value="">Pilih Bank</option>
                                        @foreach ($bank as $kn)
                                            <option value="{{ $kn->bank_id }}">{{ $kn->bank_name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-xl-4">
                                <div class="pt-2">
                                    <label for="exampleFormControlTextarea5" class="form-label mb-1">Dana ditransfer ke Rekening <i class="text-danger">*</i></label>
                                    <select class="form-control" name="bank" id="pilihbank" required>
                                            <option value="">Pilih Bank</option>
                                            @foreach ($bank as $bk)
                                                <option value="{{ $bk->ID }}">{{ $bk->BANK_NAME }} | {{ $bk->BANK_ACCOUNT_NAME }} | {{ $bk->BANK_ACCOUNT_NO }}</option>
                                            @endforeach
    
                                        </select>

                                </div>
                            </div>
                            <div class="col-xl-4">
                                {{-- <div class="pt-2">

                                    <label for="exampleFormControlTextarea5"
                                    class="form-label mb-1">Atas nama Rekening <i class="text-danger">*</i></label>
                                    <input type="text" name="anrek" placeholder="Atas nama Rekening"
                                    class="form-control" id="validationCustom04"
                                    autocomplete="off" required>
                                </div> --}}
                                <div class="pt-2">
                                    <label for="exampleFormControlTextarea5" class="form-label mb-1">Lampirkan file <i
                                            class="text-muted">(optional)</i> </label>
                                    <input name="dokumen[]" type="file" class="form-control" multiple="multiple">

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="pt-2">
                                    <label for="exampleFormControlTextarea5" class="form-label mb-1">Note <i
                                            class="text-danger">*</i></label>
                                    <textarea cols="80" id="editor1" name="note" id="deskripsi" rows="2" required>{{ old('deskripsi') }}</textarea>

                                </div>

                            </div>
                        </div>

                        <div class="card-header align-items-center d-flex">
                            <h5 class="card-title mb-0 mt-4 flex-grow-1">Detail Item Pengajuan Budget <i
                                    class="text-danger">*</i></h5>
                            <div class="flex-shrink-0">
                                <div class="form-check form-switch form-switch-right form-switch-md">
                                    {{-- <button name="add" id="add" type="button" class="btn btn-success btn-sm btn-label waves-effect waves-light">
                                        <i class="ri-add-line label-icon align-middle fs-16 me-2"></i>
                                         Tambah Item</button> --}}
                                </div>
                            </div>
                        </div>

                        <div class="card-body mb-2">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0" id="dynamicTable">
                                            <thead class="table-light">
                                                <thead class="table-light ">
                                                    <tr>
                                                        {{-- <th scope="col">No</th> --}}
                                                        <th scope="col">Keterangan</th>
                                                        <th scope="col" style="width:12%">QTY</th>
                                                        <th scope="col">Harga</th>
                                                        <th scope="col">Jumlah</th>
                                                        <th scope="col">Aksi</th>
                                                    </tr>
                                                </thead>
                                            <tbody>
                                                <tr>
                                                    {{-- <th scope="row">1</th> --}}
                                                    <td>
                                                        <input type="text" name="addmore[0][keterangan]"
                                                            class="form-control" id="validationCustom04"
                                                            autocomplete="off" placeholder="Keterangan item" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="addmore[0][qty]"
                                                            onkeyup="getJumlah(0)" class="form-control"
                                                            id="validationCustom04" autocomplete="off"
                                                            placeholder="Banyak item" required>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                            <input type="text" name="addmore[0][harga]"
                                                                onkeyup="getJumlah(0)" class="form-control rupiah text-end"
                                                                autocomplete="off" placeholder="Harga Item" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                            <input type="text" name="addmore[0][jumlah]"
                                                                class="form-control jumlahhh text-end" id="validationCustom04"
                                                                id="jumlah" autocomplete="off" readonly>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="mt-1">
                                                            {{-- <button name="add" id="add" type="button" class="btn btn-success btn-sm btn-label waves-effect waves-light">
                                                                <i class="ri-add-line label-icon align-middle fs-16 me-2"></i>
                                                                Tambah Item</button> --}}
                                                            <button name="add" id="add" type="button"
                                                                class="btn btn-soft-secondary fw-medium btn-sm">
                                                                <i class="ri-add-fill me-1 align-bottom"></i>
                                                                Tambah Item</button>

                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3">Total</td>
                                                    <td name="total">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon1">Rp.</span>
                                                            <input type="text" name="total"
                                                                class="form-control total text-end" id="validationCustom04"
                                                                id="total" autocomplete="off" readonly>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mb-4 mt-3">
                                <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                    <i class="ri-save-2-line label-icon align-middle fs-16 me-2"></i> Submit Pengajuan
                                </button>
                                <a href="{{ route('/home') }}" type="button"
                                    class="btn btn-danger btn-label waves-effect waves-light">
                                    <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('before-scripts')
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
@endpush

@push('center-scripts')
    <script src="{{ asset('assets/libs/list.js/prismjs.min.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/listjs.init.js') }}"></script>
    <script src="{{ asset('assets/libs/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/scripts/choices.min.js') }}"></script>

    {{-- <script src="{{ asset('assets/libs/sweetalert2.min.js') }}"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@push('scripts')
    <script>
        CKEDITOR.replace('editor1', {
            height: 350,
            toolbarGroups: [
                { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'align', 'blocks'] },
                '/',
                // { name: 'links' },
                { name: 'insert' },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'tools' },
                { name: 'others' },
            ],
            removeButtons: 'PageBreak,Iframe,Image,Flash,CreateDiv,Save,NewPage,Print,Templates,Underline,Subscript,Superscript'
        }); //replaces textarea with CKEditor
        
        CKEDITOR.on('instanceReady', function(evt) {
            $.ajax({
                cache: false,
                type: 'POST',
            url: '/echo/json/',
                data: {
                    json: '{"message_html": "<html><head><title></title></head><body><img src=https://via.placeholder.com/500x300 /></body></html>"}'
                },
                success: function(data) { 
                        CKEDITOR.instances.id_message_html.setData(data.message_html, {
                    callback: function() {
                        console.log('html loaded');
                    }
                });
                },
                error: function(error) {
                    console.log('error', e)
                },
                dataType: 'json'
            });
        });
        
    </script>
    <script>
        $(document).ready(function() {
            $("#pilihbank").select2({
                theme: "bootstrap",
                placeholder: "Pilih Bank"
            });
        });
        $(document).ready(function() {
            $("#norek").select2({
                theme: "bootstrap",
                placeholder: "Pilih rekening"
            });
        });
        $(document).ready(function() {
            $("#diketahui").select2({
                // theme: "bootstrap",
                placeholder: "Mengetahui Kacab/ Kabeng"
            });
            $("#diketahui").on("select2:select", function (evt) {
                var element = evt.params.data.element;
                var $element = $(element);
                
                $element.detach();
                $(this).append($element);
                $(this).trigger("change");
            });
        });
    </script>
    <script>
        // $(document).ready(function() {

        //     $('#pilihbank').on('change', function() {
        //         var bankid = this.value;
        //         console.log(bankid);
        //         $('#listloading').removeAttr('hidden');
        //         $('#norekhide').attr('hidden', 'hidden');
        //         $.ajax({
        //             url: "{{ url('bank/getnorekening') }}",
        //             type: "POST",
        //             data: {
        //                 bankid: bankid,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             dataType: 'json',
        //             success: function(result) {
        //                 $("#norek").html('');
        //                 $('#norek').html('<option value="">Pilih rekening</option>');
        //                 $.each(result, function(key, value) {
        //                     console.log(value.bank_account_no);
        //                     $("#norek").append('<option value="' + value
        //                         .bank_branch_id + '">' + value.bank_account_no +
        //                         ' | ' + value.bank_account_name + '</option>');
        //                 });
        //                 $('#norekhide').removeAttr('hidden');
        //                 $('#listloading').attr('hidden', 'hidden');
        //             }
        //         });
        //     });
        // });
    </script>
    <script>
        var i = 0;

        $("#add").click(function() {
            ++i;

            $("#dynamicTable").append(
                '<tr><td><input type="text" name="addmore[' +
                i +
                '][keterangan]" placeholder="Keterangan item" class="form-control" required /></td><td><input type="text" name="addmore[' +
                i +
                '][qty]" onkeyup="getJumlah(' + i +
                ')" placeholder="Banyak item" class="form-control" required /></td><td><div class="input-group"><span class="input-group-text" id="basic-addon1">Rp.</span><input type="text" name="addmore[' +
                i +
                '][harga]" onkeyup="getJumlah(' + i +
                ')" placeholder="Harga item" autocomplete="off" class="form-control rupiah text-end" required /></div></td><td><div class="input-group"><span class="input-group-text" id="basic-addon1">Rp.</span><input type="text" name="addmore[' +
                i +
                '][jumlah]" class="form-control jumlahhh text-end" readonly /></div></td><td><div class="mt-1"><button name="add" id="add" type="button" class="btn btn-soft-danger fw-medium btn-sm remove-tr"><i class="ri-delete-bin-2-line me-1 align-bottom"></i> Hapus Item</button></div></td></tr>'
            );
        });

        $(document).on("click", ".remove-tr", function() {
            $(this).parents("tr").remove();
            var total = 0;
            $('.jumlahhh').each(function() {
                let jumlah = $(this).val();
                jumlah = jumlah.replace(",", "").replace(".", "");

                total += parseFloat(jumlah.replace(".", ""));
            });
            $('.total').val(formatRupiah2(total));
        });
    </script>
    <script>
        function getJumlah(no) {

            var num = no;
            var iqty = $('input[name="addmore[' + num + '][qty]"]');
            var iharga = $('input[name="addmore[' + num + '][harga]"]');

            if (iharga.val() === '' || iqty.val() === '') {

                $('input[name="addmore[' + num + '][jumlah]"]').val('0');
            } else {
                var total = 0;
                var harga1 = iharga.val();
                var qty = parseInt(iqty.val());
                let value = harga1.toString();

                value = value.replace(/[^\d]/g, '');
                value = parseFloat(value)/100;
                harga = value.toFixed(2);
                var hasil = qty * harga;
                $('input[name="addmore[' + num + '][jumlah]"]').val(formatRupiah2(hasil.toFixed(2)));
                $('input[name="addmore[' + num + '][harga]"]').val(formatRupiah2(harga1));
                $('.jumlahhh').each(function() {
                    let jumlah = $(this).val();
                    jumlah = jumlah.replace(",", "").replace(".", "");
                    
                    total += parseFloat(jumlah.replace(".", ""));
                    console.log('total1 '+ total);
                });
                // value = total.replace(/[^\d]/g, '');
                value = parseFloat(total)/100;
                total = value.toFixed(2);
                $('.total').val(formatRupiah2(total));
            }

        }

        function formatRupiah2(angka) {
            
            let value = angka.toString();
            
            // Menghilangkan semua karakter selain angka
            value = value.replace(/[^\d]/g, '');
            // Mengubah nilai menjadi number dan membaginya dengan 100 untuk mengubah 2 digit terakhir menjadi desimal
            value = parseFloat(value)/100;
            value = value.toFixed(2).replace('.', ',');
            
            var number_string = value.toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

    </script>
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
