<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Ktp;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Data Kecamatan';
        // pencarian
        $kec = Kecamatan::with('kabupaten')->orderBy('kecamatan_nama', 'ASC');
        // dd($kec);
        if ($request->filled('kecamatan_nama')) {
            $kec->where('kecamatan_nama', 'LIKE', '%' . $request->kecamatan_nama . '%');
        }
        if ($request->filled('kabupaten_id')) {
            $kec->where('kabupaten_id', $request->kabupaten_id);
        }
        $kecamatan = $kec->paginate(50)->withQueryString();
        //
        $data['rs_datas'] = $kecamatan;
        $data['rs_kab'] = Kabupaten::orderBy('kabupaten_nama')->get();
        return view('master.kecamatan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Kecamatan';
        $data['rs_kab'] = Kabupaten::orderBy('kabupaten_nama')->get();
        return view('master.kecamatan.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kabupaten_id' => 'required',
            'kecamatan_nama' => 'required',
            'kecamatan_code' => 'required|digits:2',
        ]);
        // cek kode apakah sudah terpakai atau belum
        $code = Kecamatan::where('kecamatan_code', $request->kecamatan_code)->first();
        if (!empty($code)) {
            return redirect()->route('addDataKecamatan')->with('error', 'Kode Kecamatan sudah ada..')->withInput();
        }
        //
        $status = Kecamatan::create(
            [
                'kabupaten_id' => $request->kabupaten_id,
                'kecamatan_nama' => $request->kecamatan_nama,
                'kecamatan_code' => $request->kecamatan_code,
            ]
        );
        //redirect
        if ($status) {
            logActivity('Tambah Kecamatan', 'User berhasil menambah data Kecamatan');
            return redirect()->route('addDataKecamatan')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('addDataKecamatan')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Kecamatan::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Ubah Data Kecamatab';
        $data['detail'] = $detail;
        $data['rs_kab'] = Kabupaten::orderBy('kabupaten_nama')->get();
        return view('master.kecamatan.edit', $data);
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kabupaten_id' => 'required',
            'kecamatan_nama' => 'required',
            'kecamatan_code' => 'required|digits:2',
        ]);
        $detail = Kecamatan::find($id);
        if (empty($detail)) {
            return redirect()->route('dataKecamatan')->with('error', "Data tidak ditemukan");
        }
        // check jika merubah kode
        if ($detail->kecamatan_code != $request->kecamatan_code) {
            $code = Kecamatan::where('kecamatan_code', $request->kecamatan_code)->first();
            if (!empty($code)) {
                return redirect()->route('editDataKecamatan', $id)->with('error', 'Kode Kecamatan sudah ada..')->withInput();
            }
        }
        $detail->kabupaten_id = $request->kabupaten_id;
        $detail->kecamatan_nama = $request->kecamatan_nama;
        $detail->kecamatan_code = $request->kecamatan_code;
        // save
        if ($detail->save()) {
            logActivity('Ubah Kecamatan', 'User berhasil ubah data Kecamatan');
            return redirect()->route('editDataKecamatan', $id)->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('editDataKecamatan', $id)->with('success', 'Data berhasil disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Kecamatan::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', "Data tidak ditemukan");
        }
        // cek apakah ada ini digunakan sebagai referensi tabel lain
        $dataKtp = Ktp::where('kecamatan_id', $id)->count();
        $errorKtp = "";
        if ($dataKtp >= 1) {
            $errorKtp = "Kecamatan ini digunakan untuk referensi KTP";
        }
        //
        $dataKel = Kelurahan::where('kecamatan_id', $id)->count();
        $errorKec = "";
        if ($dataKel >= 1) {
            $errorKec = "Kecamatan ini digunakan untuk referensi Kelurahan";
        }
        if ($dataKtp >= 1 || $dataKel >= 1) {
            // tolak hapus
            $pesanError = $dataKtp >= 1 ? ($errorKtp . ' Dan ' . $errorKec) : $errorKec;
            return redirect()->back()->with('error', $pesanError);
        }
        if ($detail->delete()) {
            logActivity('Hapus Kecamatan', 'User berhasil hapus data Kecamatan');
            return redirect()->back()->with('success', "Data berhasil dihapus");
        } else {
            return redirect()->back()->with('error', "Data gagal dihapus");
        }
    }
}
