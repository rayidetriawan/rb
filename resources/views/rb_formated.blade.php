<!DOCTYPE html>
<html>

<head>
    <title>PDF RB</title>
    <style>
        body {
            font-size: 14px;
            font-family: 'Calibri', 'calibri', Helvetica, Arial, sans-serif;
            text-align: center;
            padding: 15px;
            border: 2px solid #000000;
        }
        table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        .ckeditor {
            text-align: left;
            border:2px solid #000000;
            padding:10px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <!-- Start your code here -->

    <table width="100%">
        <tr>
            <td><strong>{{ $createdate }}</strong></td>
            <td align="center" width="40%">
                <p style="border:2px solid #000000; padding:5px">
                    <strong> {{ $header->RB_ID }} </strong>
                </p>
            </td>
        </tr>
        <tr>
            <td><strong>Kepada :<br>
                    Yth. Pimpinan Tirta Utama Abadi <br /><br />
                    Ditempat
                </strong></td>
            <td align="center" width="40%">
                <p style="border:2px solid #000000; padding:10px; border-radius: 5px; font-size:12px">
                    <strong> NOTE: APABILA PERMOHONAN INI DISETUJUI, MAKA SURAT/ FILE INI SEKALIGUS SEBAGAI PERMOHONAN
                        OTORISASI PENCAIRAN DANA. </strong>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2"><br><br>Dengan Hormat<br /><br />
                Sehubungan dengan kebutuhan {{ $header->cabang->BRANCH_NAME }}, maka dengan ini kami mengajukan permohonan budget sebagai
                berikut:</td>
        </tr>
    </table>
    <br />
    <table border="1">
        <tr style="font-weight: bold; background-color:#E3DCDC">
            <td style="padding:5px;" align="center">#</td>
            <td style="padding:5px;">Keterangan</td>
            <td style="padding:5px;" align="center">QTY</td>
            <td style="padding:5px;" align="center">Harga</td>
            <td style="padding:5px;" align="center">Jumlah</td>
        </tr>
        @foreach($header->TrxRbDetailItem as $row => $item)
        <tr>
            <td style="padding:5px;" align="center">{{ $row+1 }}</td>
            <td style="padding:5px;">{{ $item->ITEM }}</td>
            <td style="padding:5px;" align="center">{{ $item->QTY }}</td>
            <td style="padding:5px;" align="right">@uang($item->HARGA)</td>
            <td style="padding:5px;" align="right">@uang($item->QTY*$item->HARGA)</td>
        </tr>
        @endforeach
        <tr style="font-weight: bold; background-color:#E3DCDC">
            <td style="padding:5px;" colspan="4" align="right">Total</td>

            <td style="padding:5px;" align="right">@uang($header->TOTAL_HARGA)</td>
        </tr>
    </table>
    <br />
    @if(!empty($header->KETERANGAN))
    <p style="border:2px solid #000000; padding:10px; border-radius: 5px; font-size: 12px;" align="left">
        <strong>NOTE: <br></strong>
        
        @nl2br($header->KETERANGAN)
    </p>
    @else
    <div class="ckeditor">
        <strong>NOTE :</strong><br>
        {!! $header->KET_CKEDITOR !!}
    </div>
    @endif
    <br>
    <table style="width:50%">
        <tr>
            <td>

                <strong>Mohon Dana dapat ditrasfer ke Rekening:
                    <table border="0">
                        <tr>
                            <td>Nama Bank</td>
                            <td>:</td>
                            <td>{{ $header->BANK }}</td>
                        </tr>
                        <tr>
                            <td>No Rekening</td>
                            <td>:</td>
                            <td>{{ $header->NO_REK }}</td>
                        </tr>
                        <tr>
                            <td>Nama Rekening</td>
                            <td>:</td>
                            <td>{{ $header->NAMA_REK }}</td>
                        </tr>
                    </table>
                </strong>
            </td>
        </tr>
    </table>
    <br />
    <table>
        <tr>
            <td colspan="2" style="width:100%">Demikian permohonan revisi budget ini kami buat. Atas perhatian dan
                persetujuan dari Bapak/Ibu kami ucapkan terima kasih.</td>
        </tr>
    </table>
    <br>
    <table style="font-size: 12px">
        <tr>
            <?php $i = 0; ?>
            @foreach($mengetahui as $result)
                
                @if($i % 4 == 0)
                
                </tr><tr>
                    <td colspan="4"></td>
                </tr><tr>
                @endif
                <td align="center">
                    @if($result->flag == 'MEMBUAT')Membuat, @elseif($result->flag == 'MENGETAHUI')Mengetahui,@else Menyetujui, @endif<br> 
                    <img src="http://intranet.hondamitrajaya.com/acc.png" height="50px" style="padding:5px">
                    <br>
                    <strong><u>{{ $result->karyawan->nama }}</u><br>
                    @if($result->flag == 'MEMBUAT')
                        {{ $header->karyawan->jabatan->nama_jabatan }}
                    @elseif($result->flag == 'MENYETUJUI')
                        @if($result->karyawan->jabatan->id == '3' || $result->karyawan->jabatan->id == '10' || $result->karyawan->jabatan->id == '8' || $result->karyawan->jabatan->id == '11' ) {{-- branch head, kabeng, owner, direktur --}}
                        {{ $result->karyawan->jabatan->nama_jabatan }}        
                        @else
                        {{ $result->karyawan->jabatan->nama_jabatan }} {{ $result->karyawan->departemen->nama_dept }}
                        @endif
                    @else
                        @if($result->karyawan->jabatan->id == '3' || $result->karyawan->jabatan->id == '8' || $result->karyawan->jabatan->id == '11' ) {{-- branch head, owner, direktur --}}
                        {{ $result->karyawan->jabatan->nama_jabatan }}
                        @else
                        {{ $result->karyawan->jabatan->nama_jabatan }} {{ $result->karyawan->departemen->nama_dept }}
                        @endif
                    @endif
                    
                    </strong>
                </td>

                <?php $i++; ?>
            @endforeach
        </tr>
    </table>
</body>

</html>
