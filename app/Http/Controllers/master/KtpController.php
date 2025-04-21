<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Ktp;
use App\Models\Pekerjaan;
use App\Models\Provinsi;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class KtpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $data['title'] = 'Data KTP';
        // pencarian
        $nik = $request->filled('nik') ? '%' . $request->nik . '%' : '%';
        $nama = $request->filled('nama') ? '%' . $request->nama . '%' : '%';
        $provinsi = $request->filled('provinsi') ? $request->provinsi : '%';
        $kabupaten = $request->filled('kabupaten') ? $request->kabupaten : '%';
        $kecamatan = $request->filled('kecamatan') ? $request->kecamatan : '%';
        $kelurahan = $request->filled('kelurahan') ? $request->kelurahan : '%';
        $pekerjaan = $request->filled('pekerjaan') ? $request->pekerjaan : '%';
        //
        $data['rs_datas'] = Ktp::
            with(['provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'pekerjaan'])
            ->where('ktp_nik', 'LIKE', $nik)
            ->where('ktp_nama', 'LIKE', $nama)
            ->whereRelation('provinsi', 'provinsi_id', 'LIKE', $provinsi)
            ->whereRelation('kabupaten', 'kabupaten_id', 'LIKE', $kabupaten)
            ->whereRelation('kecamatan', 'kecamatan_id', 'LIKE', $kecamatan)
            ->whereRelation('kelurahan', 'kelurahan_id', 'LIKE', $kelurahan)
            ->whereRelation('pekerjaan', 'pekerjaan_id', 'LIKE', $pekerjaan)
            ->orderBy('ktp_nama', 'ASC')
            ->paginate(10)
            ->appends($request->all());
        // referensi data
        $data['rs_prov'] = Provinsi::orderBy('provinsi_nama')->get();
        $data['rs_kab'] = Kabupaten::orderBy('kabupaten_nama')->get();
        $data['rs_kec'] = Kecamatan::orderBy('kecamatan_nama')->get();
        $data['rs_kel'] = Kelurahan::orderBy('kelurahan_nama')->get();
        $data['rs_pekerjaan'] = Pekerjaan::orderBy('pekerjaan_nama')->get();
        // dd($data);
        return view('master.ktp.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role_id == 'R0003') {
            abort(403, 'Unauthorized');
        }
        $data['title'] = 'Tambah Data KTP';
        $data['rs_prov'] = Provinsi::orderBy('provinsi_nama')->get();
        $data['rs_pekerjaan'] = Pekerjaan::orderBy('pekerjaan_nama')->get();
        return view('master.ktp.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ktp_nama' => 'required',
            'ktp_tempat_lahir' => 'required',
            'ktp_tgl_lahir' => 'required|date',
            'ktp_jk' => 'required',
            'ktp_darah' => 'required',
            'ktp_dusun' => 'required',
            'ktp_rt' => 'required',
            'ktp_rw' => 'required',
            'pekerjaan_id' => 'required',
            'kelurahan_id' => 'required',
            'kecamatan_id' => 'required',
            'kabupaten_id' => 'required',
            'provinsi_id' => 'required',
            'ktp_agama' => 'required',
            'ktp_perkawinan' => 'required',
            'ktp_negara' => 'required',
            'ktp_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // datas
        $provinsi = Provinsi::find($request->provinsi_id);
        $kabupaten = Kabupaten::find($request->kabupaten_id);
        $kecamatan = Kecamatan::find($request->kecamatan_id);
        // kode wilayah
        $kodeWilayah = $provinsi->provinsi_code . $kabupaten->kabupaten_code . $kecamatan->kecamatan_code;
        // tanggal lahir
        $tglLahir = $request->ktp_tgl_lahir;
        $split = explode('-', $tglLahir);
        //
        $kodeTanggalLahir = $split[2] . $split[1] . substr($split[0], -2);
        //
        $urutan = DB::table('ktp')
            ->select('ktp_nik', DB::raw('SUBSTRING(ktp_nik, 1, 6) AS kode'), DB::raw('SUBSTRING(ktp_nik, -4, 4) AS urut'))
            ->whereRaw('SUBSTRING(ktp_nik, 1, 12) = ?', [$kodeWilayah . $kodeTanggalLahir])
            ->orderBy(DB::raw('SUBSTRING(ktp_nik, -4, 4)'), 'DESC')
            ->first();
        if (!empty($urutan)) {
            $terakhir = (int) $urutan->urut;
            $urut_baru = $terakhir + 1;
            $format_urutan = str_pad($urut_baru, 4, '0', STR_PAD_LEFT);
        } else {
            $format_urutan = '0001';
        }
        $nik_kode = $kodeWilayah . $kodeTanggalLahir . $format_urutan;
        // Simpan gambar ke folder public/foto/
        $file = $request->file('ktp_image');
        $namaFile = $nik_kode . '.' . $file->getClientOriginalExtension();
        if (!$file->move(public_path('foto'), $namaFile)) {
            return redirect()->back()->with('error', "Foto gagal diupload");
        }
        //
        $ktp_path = 'foto/' . $namaFile;
        try {
            $status = Ktp::create(
                [
                    'ktp_nik' => $nik_kode,
                    'ktp_nama' => $request->ktp_nama,
                    'ktp_tempat_lahir' => $request->ktp_tempat_lahir,
                    'ktp_tgl_lahir' => $request->ktp_tgl_lahir,
                    'ktp_umur' => $request->ktp_umur,
                    'ktp_jk' => $request->ktp_jk,
                    'ktp_darah' => $request->ktp_darah,
                    'ktp_dusun' => $request->ktp_dusun,
                    'ktp_rt' => $request->ktp_rt,
                    'ktp_rw' => $request->ktp_rw,
                    'pekerjaan_id' => $request->pekerjaan_id,
                    'kelurahan_id' => $request->kelurahan_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'provinsi_id' => $request->provinsi_id,
                    'ktp_agama' => $request->ktp_agama,
                    'ktp_perkawinan' => $request->ktp_perkawinan,
                    'ktp_negara' => $request->ktp_negara,
                    'ktp_path' => $ktp_path,
                    'ktp_dibuat' => date('Y-m-d'),
                ]
            );
            //redirect
            if ($status) {
                logActivity('Tambah KTP', 'User berhasil menambah data KTP');
                return redirect()->route('addDataKtp')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->route('addDataKtp')->with('error', 'Data gagal disimpan');
            }
        } catch (\Throwable $th) {
            dd($th->errorInfo);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ktp_nik)
    {
        if (Auth::user()->role_id == 'R0003') {
            abort(403, 'Unauthorized');
        }
        $detail = Ktp::find($ktp_nik);
        // dd($detail);
        if (empty($detail)) {
            return redirect()->route('dataKtp')->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Ubah Data KTP';
        $data['rs_prov'] = Provinsi::orderBy('provinsi_nama')->get();
        $data['rs_pekerjaan'] = Pekerjaan::orderBy('pekerjaan_nama')->get();
        $data['detail'] = $detail;
        return view('master.ktp.edit', $data);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ktp_nik)
    {
        $detail = Ktp::find($ktp_nik);
        if (empty($detail)) {
            return redirect()->route('dataKtp')->with('error', 'Data tidak ditemukan');
        }
        $request->validate([
            'ktp_nama' => 'required',
            'ktp_tempat_lahir' => 'required',
            'ktp_tgl_lahir' => 'required|date',
            'ktp_jk' => 'required',
            'ktp_darah' => 'required',
            'ktp_dusun' => 'required',
            'ktp_rt' => 'required',
            'ktp_rw' => 'required',
            'pekerjaan_id' => 'required',
            'kelurahan_id' => 'required',
            'kecamatan_id' => 'required',
            'kabupaten_id' => 'required',
            'provinsi_id' => 'required',
            'ktp_agama' => 'required',
            'ktp_perkawinan' => 'required',
            'ktp_negara' => 'required',
        ]);
        $res_nik = $detail->ktp_nik;
        // cek nik kode
        if ($request->provinsi_id != $detail->provinsi_id || $request->kabupaten_id != $detail->kabupaten_id || $request->kecamatan_id != $detail->kecamatan_id) {
            // kode wilayah
            $provinsi = Provinsi::find($request->provinsi_id);
            $kabupaten = Kabupaten::find($request->kabupaten_id);
            $kecamatan = Kecamatan::find($request->kecamatan_id);
            $kodeWilayah = $provinsi->provinsi_code . $kabupaten->kabupaten_code . $kecamatan->kecamatan_code;
            // tanggal lahir
            $tglLahir = $request->ktp_tgl_lahir;
            $split = explode('-', $tglLahir);
            //
            $kodeTanggalLahir = $split[2] . $split[1] . substr($split[0], -2);
            //
            $urutan = DB::table('ktp')
                ->select('ktp_nik', DB::raw('SUBSTRING(ktp_nik, 1, 6) AS kode'), DB::raw('SUBSTRING(ktp_nik, -4, 4) AS urut'))
                ->whereRaw('SUBSTRING(ktp_nik, 1, 12) = ?', [$kodeWilayah . $kodeTanggalLahir])
                ->orderBy(DB::raw('SUBSTRING(ktp_nik, -4, 4)'), 'DESC')
                ->first();
            if (!empty($urutan)) {
                $terakhir = (int) $urutan->urut;
                $urut_baru = $terakhir + 1;
                $format_urutan = str_pad($urut_baru, 4, '0', STR_PAD_LEFT);
            } else {
                $format_urutan = '0001';
            }
            $res_nik = $kodeWilayah . $kodeTanggalLahir . $format_urutan;
        }
        $ktp_path = $detail->ktp_path;
        if ($request->hasFile('ktp_image')) {
            $request->validate([
                'ktp_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            // upload
            $file = $request->file('ktp_image');
            $namaFile = $res_nik . '.' . $file->getClientOriginalExtension();
            if (!$file->move(public_path('foto'), $namaFile)) {
                return redirect()->back()->with('error', "Foto gagal diupload");
            }
            //
            $ktp_path = 'foto/' . $namaFile;
        }
        // update
        $detail->ktp_nik = $res_nik;
        $detail->ktp_nama = $request->ktp_nama;
        $detail->ktp_tempat_lahir = $request->ktp_tempat_lahir;
        $detail->ktp_tgl_lahir = $request->ktp_tgl_lahir;
        $detail->ktp_umur = $request->ktp_umur;
        $detail->ktp_jk = $request->ktp_jk;
        $detail->ktp_darah = $request->ktp_darah;
        $detail->ktp_dusun = $request->ktp_dusun;
        $detail->ktp_rt = $request->ktp_rt;
        $detail->ktp_rw = $request->ktp_rw;
        $detail->pekerjaan_id = $request->pekerjaan_id;
        $detail->kelurahan_id = $request->kelurahan_id;
        $detail->kecamatan_id = $request->kecamatan_id;
        $detail->kabupaten_id = $request->kabupaten_id;
        $detail->provinsi_id = $request->provinsi_id;
        $detail->ktp_agama = $request->ktp_agama;
        $detail->ktp_perkawinan = $request->ktp_perkawinan;
        $detail->ktp_negara = $request->ktp_negara;
        $detail->ktp_path = $ktp_path;
        $detail->ktp_dibuat = date('Y-m-d');
        // save
        if ($detail->update()) {
            logActivity('Ubah KTP', 'User berhasil ubah data KTP');
            return redirect()->route('editDataKtp', $res_nik)->with('success', "Data berhasil diubah");
        } else {
            return redirect()->route('editDataKtp', $res_nik)->with('error', "Data gagal diubah");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //

    public function get_all_kabupaten_by_prov(string $provinsi_id)
    {
        $kabupaten = Kabupaten::where('provinsi_id', $provinsi_id)->orderBy('kabupaten_nama')->get();
        return response()->json($kabupaten);
    }

    public function get_all_kecamatan_by_kab(string $kabupaten_id)
    {
        $kecamatan = Kecamatan::where('kabupaten_id', $kabupaten_id)->orderBy('kecamatan_nama')->get();
        return response()->json($kecamatan);
    }

    public function get_all_kelurahan_by_kec(string $kecamatan_id)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $kecamatan_id)->orderBy('kelurahan_nama')->get();
        return response()->json($kelurahan);
    }

    public function download(Request $request)
    {
        $templatePath = storage_path('app/templates/data_ktp_template.xlsx');

        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        // params
        $nik = $request->filled('nik') ? '%' . $request->nik . '%' : '%';
        $nama = $request->filled('nama') ? '%' . $request->nama . '%' : '%';
        $provinsi = $request->filled('provinsi') ? $request->provinsi : '%';
        $kabupaten = $request->filled('kabupaten') ? $request->kabupaten : '%';
        $kecamatan = $request->filled('kecamatan') ? $request->kecamatan : '%';
        $kelurahan = $request->filled('kelurahan') ? $request->kelurahan : '%';
        $pekerjaan = $request->filled('pekerjaan') ? $request->pekerjaan : '%';
        // data
        $rsKtp = Ktp::
            with(['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])
            ->where('ktp_nik', 'LIKE', $nik)
            ->where('ktp_nama', 'LIKE', $nama)
            ->whereRelation('provinsi', 'provinsi_id', 'LIKE', $provinsi)
            ->whereRelation('kabupaten', 'kabupaten_id', 'LIKE', $kabupaten)
            ->whereRelation('kecamatan', 'kecamatan_id', 'LIKE', $kecamatan)
            ->whereRelation('kelurahan', 'kelurahan_id', 'LIKE', $kelurahan)
            ->whereRelation('pekerjaan', 'pekerjaan_id', 'LIKE', $pekerjaan)
            ->orderBy('ktp_nama', 'ASC')->get();
        $row = 5;
        $no = 1;
        foreach ($rsKtp as $ktp) {
            $sheet->insertNewRowBefore(($row + 1), 1);
            // alamat
            $alamat = 'RT ' . $ktp->ktp_rt . ', Rw ' . $ktp->ktp_rw . ', ' . $ktp->kelurahan->kelurahan_nama . ', ' . $ktp->kecamatan->kecamatan_nama . ', ' . $ktp->kabupaten->kabupaten_nama . ', ' . $ktp->provinsi->provinsi_nama;
            //
            $sheet->setCellValue('B' . $row, $no);
            $sheet->setCellValueExplicit('C' . $row, $ktp->ktp_nik, DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $ktp->ktp_nama);
            $sheet->setCellValue('E' . $row, $ktp->ktp_tempat_lahir);
            $sheet->setCellValue('F' . $row, Carbon::parse($ktp->ktp_tgl_lahir)->translatedFormat('d F Y'));
            $sheet->setCellValue('G' . $row, $ktp->ktp_umur);
            $sheet->setCellValue('H' . $row, $ktp->ktp_jk);
            $sheet->setCellValue('I' . $row, $ktp->ktp_darah);
            $sheet->setCellValue('J' . $row, $ktp->ktp_agama);
            $sheet->setCellValue('K' . $row, $ktp->pekerjaan->pekerjaan_nama);
            $sheet->setCellValue('L' . $row, $alamat);
            $sheet->setCellValue('M' . $row, $ktp->ktp_perkawinan);
            $sheet->setCellValue('N' . $row, $ktp->ktp_negara);
            $sheet->setCellValue('O' . $row, Carbon::parse($ktp->ktp_dibuat)->translatedFormat('d F Y'));
            $row++;
            $no++;
        }
        $sheet->removeRow($row, 1);
        //
        $writer = new Xlsx($spreadsheet);
        $filename = 'DATA-KTP.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
        logActivity('Download Excel KTP', 'User berhasil download excel data KTP');
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        // params
        $nik = $request->filled('nik') ? '%' . $request->nik . '%' : '%';
        $nama = $request->filled('nama') ? '%' . $request->nama . '%' : '%';
        $provinsi = $request->filled('provinsi') ? $request->provinsi : '%';
        $kabupaten = $request->filled('kabupaten') ? $request->kabupaten : '%';
        $kecamatan = $request->filled('kecamatan') ? $request->kecamatan : '%';
        $kelurahan = $request->filled('kelurahan') ? $request->kelurahan : '%';
        $pekerjaan = $request->filled('pekerjaan') ? $request->pekerjaan : '%';
        // data
        $rsKtp = Ktp::
            with(['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])
            ->where('ktp_nik', 'LIKE', $nik)
            ->where('ktp_nama', 'LIKE', $nama)
            ->whereRelation('provinsi', 'provinsi_id', 'LIKE', $provinsi)
            ->whereRelation('kabupaten', 'kabupaten_id', 'LIKE', $kabupaten)
            ->whereRelation('kecamatan', 'kecamatan_id', 'LIKE', $kecamatan)
            ->whereRelation('kelurahan', 'kelurahan_id', 'LIKE', $kelurahan)
            ->whereRelation('pekerjaan', 'pekerjaan_id', 'LIKE', $pekerjaan)
            ->orderBy('ktp_nama', 'ASC')->get();
        $pdf = Pdf::loadView('master.ktp.ktp_pdf', compact('rsKtp'))->setPaper('A4', 'landscape');
        logActivity('Download PDF KTP', 'User berhasil download pdf data KTP');
        // return $pdf->stream('KTP.pdf');
        return $pdf->download('DATA-KTP.pdf');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv',
        ]);
        // referensi data
        $error = 0;
        $dataError = [];
        $gender = ['L', 'P'];
        $agama = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $success = 0;
        // file
        $file = $request->file('file_excel');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        // jumlah data
        $highestRow = $sheet->getHighestRow();

        for ($row = 6; $row <= $highestRow; $row++) {
            $rowData[$row]['nama'] = $sheet->getCell('C' . $row)->getValue();
            $rowData[$row]['tmpLahir'] = $sheet->getCell('D' . $row)->getValue();
            $rowData[$row]['tglLahir'] = Date::excelToDateTimeObject($sheet->getCell('E' . $row)->getValue())->format('Y-m-d');
            $rowData[$row]['gender'] = $sheet->getCell('F' . $row)->getValue();
            $rowData[$row]['gol_darah'] = $sheet->getCell('G' . $row)->getValue();
            $rowData[$row]['agama'] = $sheet->getCell('H' . $row)->getValue();
            $rowData[$row]['pekerjaan'] = $sheet->getCell('I' . $row)->getValue();
            $rowData[$row]['rt'] = $sheet->getCell('J' . $row)->getValue();
            $rowData[$row]['rw'] = $sheet->getCell('K' . $row)->getValue();
            $rowData[$row]['dusun'] = $sheet->getCell('L' . $row)->getValue();
            $rowData[$row]['kelurahan'] = $sheet->getCell('M' . $row)->getValue();
            $rowData[$row]['kecamatan'] = $sheet->getCell('N' . $row)->getValue();
            $rowData[$row]['kabupaten'] = $sheet->getCell('O' . $row)->getValue();
            $rowData[$row]['provinsi'] = $sheet->getCell('P' . $row)->getValue();
            $rowData[$row]['status'] = $sheet->getCell('Q' . $row)->getValue();
            $rowData[$row]['kewarganegaraan'] = $sheet->getCell('R' . $row)->getValue();
            // filter data
            if (!in_array($rowData[$row]['gender'], $gender)) {
                $dataError[$row]['row'] = $row;
                $dataError[$row]['column'] = 'Jenis kelamin';
                $dataError[$row]['pesan'] = 'Data jenis kelamin tidak sesuai';
                $error++;
            }
            if (!in_array($rowData[$row]['agama'], $agama)) {
                $dataError[$row]['row'] = $row;
                $dataError[$row]['column'] = 'Agama';
                $dataError[$row]['pesan'] = 'Data agama tidak sesuai';
                $error++;
            }
            // pekerjaan
            $pkrjn = Pekerjaan::whereRaw('LOWER(pekerjaan_nama) = ?', strtolower($rowData[$row]['pekerjaan']))->first();
            if (empty($pkrjn)) {
                $dataError[$row]['row'] = $row;
                $dataError[$row]['column'] = 'Pekerjaan';
                $dataError[$row]['pesan'] = 'Data pekerjaan tidak ditemukan, silahkan cek didatabase';
                $error++;
            } else {
                $rowData[$row]['pekerjaan_id'] = $pkrjn->id;
            }
            // kelurahan
            $kel = Kelurahan::whereRaw('LOWER(kelurahan_nama) = ?', strtolower($rowData[$row]['kelurahan']))->first();
            if (empty($kel)) {
                $dataError[$row]['row'] = $row;
                $dataError[$row]['column'] = 'Kelurahan';
                $dataError[$row]['pesan'] = 'Data kelurahan tidak ditemukan, silahkan cek didatabase';
                $error++;
            } else {
                $rowData[$row]['kel_id'] = $kel->id;
                $rowData[$row]['kel_code'] = $kel->kelurahan_code;
            }
            // kecamatan
            $kec = Kecamatan::whereRaw('LOWER(kecamatan_nama) = ?', strtolower($rowData[$row]['kecamatan']))->first();
            if (empty($kec)) {
                $dataError[$row]['row'] = $row;
                $dataError[$row]['column'] = 'Kelurahan';
                $dataError[$row]['pesan'] = 'Data kecamatan tidak ditemukan, silahkan cek didatabase';
                $error++;
            } else {
                $rowData[$row]['kec_id'] = $kec->id;
                $rowData[$row]['kec_code'] = $kec->kecamatan_code;
            }
            // kabupaten
            $kab = Kabupaten::whereRaw('LOWER(kabupaten_nama) = ?', strtolower($rowData[$row]['kabupaten']))->first();
            if (empty($kab)) {
                $dataError[$row]['row'] = $row;
                $dataError[$row]['column'] = 'Kelurahan';
                $dataError[$row]['pesan'] = 'Data kabupaten tidak ditemukan, silahkan cek didatabase';
                $error++;
            } else {
                $rowData[$row]['kab_id'] = $kab->id;
                $rowData[$row]['kab_code'] = $kab->kabupaten_code;
            }
            // provinsi
            $prov = Provinsi::whereRaw('LOWER(provinsi_nama) = ?', strtolower($rowData[$row]['provinsi']))->first();
            if (empty($prov)) {
                $dataError[$row]['row'] = $row;
                $dataError[$row]['column'] = 'Kelurahan';
                $dataError[$row]['pesan'] = 'Data provinsi tidak ditemukan, silahkan cek didatabase';
                $error++;
            } else {
                $rowData[$row]['prov_id'] = $prov->id;
                $rowData[$row]['prov_code'] = $prov->provinsi_code;
            }
            // NIK KODE
            if (!empty($kec) && !empty($kab) && !empty($prov)) {
                $kode_lokasi = $prov->provinsi_code . $kab->kabupaten_code . $kec->kecamatan_code;
                $split = explode('-', $rowData[$row]['tglLahir']);
                $kode_tgl_lahir = $split[2] . $split[1] . substr($split[0], -2);
                $urutan = DB::table('ktp')
                    ->select('ktp_nik', DB::raw('SUBSTRING(ktp_nik, 1, 6) AS kode'), DB::raw('SUBSTRING(ktp_nik, -4, 4) AS urut'))
                    ->whereRaw('SUBSTRING(ktp_nik, 1, 12) = ?', [$kode_lokasi])
                    ->orderBy(DB::raw('SUBSTRING(ktp_nik, -4, 4)'), 'DESC')
                    ->first();
                if (!empty($urutan)) {
                    $terakhir = (int) $urutan->urut;
                    $urut_baru = $terakhir + 1;
                    $format_urutan = str_pad($urut_baru, 4, '0', STR_PAD_LEFT);
                } else {
                    $format_urutan = '0001';
                }
                //
                $nik_kode = $kode_lokasi . $kode_tgl_lahir . $format_urutan;
                if (!empty(Ktp::where('ktp_nik', $nik_kode)->first())) {
                    $dataError[$row]['row'] = $row;
                    $dataError[$row]['column'] = 'NIK';
                    $dataError[$row]['pesan'] = 'NIK Sudah ada, silahkan cek didatabase';
                    $error++;
                }
            }
        }
        if ($error > 0) {
            $pesan = "";
            foreach (array_values($dataError) as $key => $val) {
                if ($key == '0') {
                    $pesan .= "Row " . $val["row"] . ", " . $val["pesan"];
                } else {
                    $pesan .= "<br>Row " . $val["row"] . ", " . $val["pesan"];
                }
            }
            return redirect()->back()->with('error', $pesan);
        }

        // filtered data
        $datas = [];
        foreach ($rowData as $key => $value) {
            // kode
            $split = explode('-', $value['tglLahir']);
            $kode_tgl_lahir = $split[2] . $split[1] . substr($split[0], -2);
            // kode lokasi
            $kode_lokasi = $value['prov_code'] . $value['kab_code'] . $value['kec_code'];
            $urutan = DB::table('ktp')
                ->select('ktp_nik', DB::raw('SUBSTRING(ktp_nik, 1, 6) AS kode'), DB::raw('SUBSTRING(ktp_nik, -4, 4) AS urut'))
                ->whereRaw('SUBSTRING(ktp_nik, 1, 12) = ?', [$kode_lokasi])
                ->orderBy(DB::raw('SUBSTRING(ktp_nik, -4, 4)'), 'DESC')
                ->first();
            if (!empty($urutan)) {
                $terakhir = (int) $urutan->urut;
                $urut_baru = $terakhir + 1;
                $format_urutan = str_pad($urut_baru, 4, '0', STR_PAD_LEFT);
            } else {
                $format_urutan = '0001';
            }
            $nik_kode = $kode_lokasi . $kode_tgl_lahir . $format_urutan;
            // cek jika data sudah ada

            $datas = [
                'ktp_nik' => $nik_kode,
                'ktp_nama' => $value['nama'],
                'ktp_tempat_lahir' => $value['tmpLahir'],
                'ktp_tgl_lahir' => $value['tglLahir'],
                'ktp_umur' => Carbon::parse($value['tglLahir'])->age,
                'ktp_jk' => $value['gender'],
                'ktp_darah' => $value['gol_darah'],
                'ktp_dusun' => $value['dusun'],
                'ktp_rt' => $value['rt'],
                'ktp_rw' => $value['rw'],
                'kelurahan_id' => $value['kel_id'],
                'kecamatan_id' => $value['kec_id'],
                'kabupaten_id' => $value['kab_id'],
                'provinsi_id' => $value['prov_id'],
                'ktp_agama' => $value['agama'],
                'pekerjaan_id' => $value['pekerjaan_id'],
                'ktp_perkawinan' => $value['status'],
                'ktp_negara' => $value['kewarganegaraan'],
                'ktp_path' => 'foto/default.png',
                'ktp_dibuat' => date('Y-m-d'),
            ];
            if (Ktp::create($datas)) {
                $success++;
            }
        }
        logActivity('Import KTP', 'User berhasil import data KTP');
        $pesan = "Sebanyak " . $success . " Data berhasil diimport";
        return redirect()->back()->with('success', $pesan);
    }
}
