<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Ktp;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Data Provinsi';
        $prov = Provinsi::orderBy('provinsi_nama', 'ASC');
        if ($request->filled('provinsi_nama')) {
            $prov->where('provinsi_nama', 'LIKE', '%' . $request->provinsi_nama . '%');
        }
        $res_prov = $prov->paginate(20)->withQueryString();
        $data['rs_datas'] = $res_prov;
        return view('master.provinsi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Provinsi';
        return view('master.provinsi.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'provinsi_nama' => 'required',
            'provinsi_code' => 'required|digits:2',
        ]);
        // cek kode apakah sudah terpakai atau belum
        $code = Provinsi::where('provinsi_code', $request->provinsi_code)->first();
        if (!empty($code)) {
            return redirect()->route('addDataProvinsi')->with('error', 'Kode Provinsi sudah ada..')->withInput();
        }
        //
        $status = Provinsi::create(
            [
                'provinsi_nama' => $request->provinsi_nama,
                'provinsi_code' => $request->provinsi_code,
            ]
        );
        //redirect
        if ($status) {
            logActivity('Tambah Provinsi', 'User berhasil menambah data Provinsi');
            return redirect()->route('addDataProvinsi')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('addDataProvinsi')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Provinsi::find($id);
        if (empty($detail)) {
            return redirect()->route('dataProvinsi')->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Ubah Data Provinsi';
        $data['detail'] = $detail;
        return view('master.provinsi.edit', $data);
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
            'provinsi_nama' => 'required',
            'provinsi_code' => 'required|digits:2',
        ]);

        $detail = Provinsi::find($id);
        // check jika merubah kode
        if ($detail->provinsi_code != $request->provinsi_code) {
            $code = Provinsi::where('provinsi_code', $request->provinsi_code)->first();
            if (!empty($code)) {
                return redirect()->route('editDataProvinsi', $id)->with('error', 'Kode Provinsi sudah ada..')->withInput();
            }
        }
        $detail->provinsi_nama = $request->provinsi_nama;
        $detail->provinsi_code = $request->provinsi_code;
        // save
        if ($detail->save()) {
            logActivity('Ubah Provinsi', 'User berhasil ubah data Provinsi');
            return redirect()->route('editDataProvinsi', $id)->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('editDataProvinsi', $id)->with('success', 'Data berhasil disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Provinsi::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', "Data tidak ditemukan");
        }
        // cek apakah ada ini digunakan sebagai referensi tabel lain
        $dataKtp = Ktp::where('provinsi_id', $id)->count();
        $errorKtp = "";
        if ($dataKtp >= 1) {
            $errorKtp = "Provinsi ini digunakan untuk referensi KTP";
        }
        //
        $dataKab = Kabupaten::where('provinsi_id', $id)->count();
        $errorKab = "";
        if ($dataKab >= 1) {
            $errorKab = "Provinsi ini digunakan untuk referensi Kabupaten";
        }
        if ($dataKtp >= 1 || $dataKab >= 1) {
            // tolak hapus
            $pesanError = $dataKtp >= 1 ? ($errorKtp . ' Dan ' . $errorKab) : $errorKab;
            return redirect()->back()->with('error', $pesanError);
        }
        if ($detail->delete()) {
            logActivity('Hapus Provinsi', 'User berhasil hapus data Provinsi');
            return redirect()->back()->with('success', "Data berhasil dihapus");
        } else {
            return redirect()->back()->with('error', "Data gagal dihapus");
        }
    }
}
