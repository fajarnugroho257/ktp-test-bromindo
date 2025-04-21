<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Ktp;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Data Kabupaten';
        // pencarian
        $kab = Kabupaten::with('provinsi')->orderBy('kabupaten_nama', 'ASC');
        if ($request->filled('kabupaten_nama')) {
            $kab->where('kabupaten_nama', 'LIKE', '%' . $request->kabupaten_nama . '%');
        }
        if ($request->filled('provinsi_id')) {
            $kab->where('provinsi_id', $request->provinsi_id);
        }
        $kabupaten = $kab->paginate(20)->withQueryString();
        $data['rs_datas'] = $kabupaten;
        $data['rs_prov'] = Provinsi::orderBy('provinsi_nama')->get();
        return view('master.kabupaten.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Kabupaten';
        $data['rs_prov'] = Provinsi::orderBy('provinsi_nama')->get();
        return view('master.kabupaten.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'provinsi_id' => 'required',
            'kabupaten_nama' => 'required',
            'kabupaten_code' => 'required|digits:2',
        ]);
        // cek kode apakah sudah terpakai atau belum
        $code = Kabupaten::where('kabupaten_code', $request->kabupaten_code)->first();
        if (!empty($code)) {
            return redirect()->route('addDataKabupaten')->with('error', 'Kode Kabupaten sudah ada..')->withInput();
        }
        //
        $status = Kabupaten::create(
            [
                'provinsi_id' => $request->provinsi_id,
                'kabupaten_nama' => $request->kabupaten_nama,
                'kabupaten_code' => $request->kabupaten_code,
            ]
        );
        //redirect
        if ($status) {
            logActivity('Tambah Kabupaten', 'User berhasil menambah data Kabupaten');
            return redirect()->route('addDataKabupaten')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('addDataKabupaten')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Kabupaten::find($id);
        if (empty($detail)) {
            return redirect()->route('dataKabupaten')->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Ubah Data Provinsi';
        $data['detail'] = $detail;
        $data['rs_prov'] = Provinsi::orderBy('provinsi_nama')->get();
        return view('master.kabupaten.edit', $data);
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
            'provinsi_id' => 'required',
            'kabupaten_nama' => 'required',
            'kabupaten_code' => 'required|digits:2',
        ]);
        $detail = Kabupaten::find($id);
        if (empty($detail)) {
            return redirect()->route('dataKabupaten')->with('error', "Data tidak ditemukan");
        }
        // check jika merubah kode
        if ($detail->kabupaten_code != $request->kabupaten_code) {
            $code = Kabupaten::where('kabupaten_code', $request->kabupaten_code)->first();
            if (!empty($code)) {
                return redirect()->route('editDataKabupaten', $id)->with('error', 'Kode Kabupaten sudah ada..')->withInput();
            }
        }
        $detail->provinsi_id = $request->provinsi_id;
        $detail->kabupaten_nama = $request->kabupaten_nama;
        $detail->kabupaten_code = $request->kabupaten_code;
        // save
        if ($detail->save()) {
            logActivity('Ubah Kabupaten', 'User berhasil ubah data Kabupaten');
            return redirect()->route('editDataKabupaten', $id)->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('editDataKabupaten', $id)->with('success', 'Data berhasil disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Kabupaten::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', "Data tidak ditemukan");
        }
        // cek apakah ada ini digunakan sebagai referensi tabel lain
        $dataKtp = Ktp::where('kabupaten_id', $id)->count();
        $errorKtp = "";
        if ($dataKtp >= 1) {
            $errorKtp = "Kabupaten ini digunakan untuk referensi KTP";
        }
        //
        $dataKec = Kecamatan::where('kabupaten_id', $id)->count();
        $errorKec = "";
        if ($dataKec >= 1) {
            $errorKec = "Kabupaten ini digunakan untuk referensi Kecamatan";
        }
        if ($dataKtp >= 1 || $dataKec >= 1) {
            // tolak hapus
            $pesanError = $dataKtp >= 1 ? ($errorKtp . ' Dan ' . $errorKec) : $errorKec;
            return redirect()->back()->with('error', $pesanError);
        }
        if ($detail->delete()) {
            logActivity('Hapus Kabupaten', 'User berhasil hapus data Kabupaten');
            return redirect()->back()->with('success', "Data berhasil dihapus");
        } else {
            return redirect()->back()->with('error', "Data gagal dihapus");
        }
    }


}
