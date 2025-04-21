<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Pengguna</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>DATA KTP</h3>
    <table>
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">NIK</th>
                <th style="text-align: center">Nama</th>
                <th style="text-align: center">Tempat Tanggal Lahir</th>
                <th style="text-align: center">Umur</th>
                <th style="text-align: center">JK</th>
                <th style="text-align: center">Gol Darah</th>
                <th style="text-align: center">Agama</th>
                <th style="text-align: center">Pekerjaan</th>
                <th style="text-align: center">Alamat</th>
                <th style="text-align: center">Status</th>
                <th style="text-align: center">Warganegaraan</th>
                <th style="text-align: center">Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                use Carbon\Carbon;
            @endphp
            @foreach ($rsKtp as $ktp)
                @php
                    // alamat
                    $alamat =
                        'RT ' .
                        $ktp->ktp_rt .
                        ', Rw ' .
                        $ktp->ktp_rw .
                        ', ' .
                        $ktp->kelurahan->kelurahan_nama .
                        ', ' .
                        $ktp->kecamatan->kecamatan_nama .
                        ', ' .
                        $ktp->kabupaten->kabupaten_nama .
                        ', ' .
                        $ktp->provinsi->provinsi_nama;
                @endphp
                <tr>
                    <td style="text-align: center">{{ $no++ }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_nik }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_nama }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_tempat_lahir }},
                        {{ Carbon::parse($ktp->ktp_tgl_lahir)->translatedFormat('d F Y') }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_umur }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_jk }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_darah }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_agama }}</td>
                    <td style="text-align: center">{{ $ktp->pekerjaan->pekerjaan_nama }}</td>
                    <td>{{ $alamat }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_perkawinan }}</td>
                    <td style="text-align: center">{{ $ktp->ktp_negara }}</td>
                    <td style="text-align: center">{{ Carbon::parse($ktp->ktp_dibuat)->translatedFormat('d F Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
