<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Ktp;
use Illuminate\Http\Request;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Data Kelurahan';
        // pencarian
        $kel = Kelurahan::with('kecamatan')->orderBy('kelurahan_nama', 'ASC');
        if ($request->filled('kelurahan_nama')) {
            $kel->where('kelurahan_nama', 'LIKE', '%' . $request->kelurahan_nama . '%');
        }
        if ($request->filled('kecamatan_id')) {
            $kel->where('kecamatan_id', $request->kecamatan_id);
        }
        $kelurahan = $kel->paginate(50)->withQueryString();
        // end pencarian
        $data['rs_datas'] = $kelurahan;
        $data['rs_kec'] = Kecamatan::orderBy('kecamatan_nama')->get();
        return view('master.kelurahan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Kelurahan';
        $data['rs_kec'] = Kecamatan::orderBy('kecamatan_nama')->get();
        return view('master.kelurahan.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required',
            'kelurahan_nama' => 'required',
            'kelurahan_code' => 'required|digits:2',
        ]);
        // cek kode apakah sudah terpakai atau belum
        $code = Kelurahan::where('kelurahan_code', $request->kelurahan_code)->first();
        if (!empty($code)) {
            return redirect()->route('addDataKelurahan')->with('error', 'Kode Kelurahan sudah ada..')->withInput();
        }
        //
        $status = Kelurahan::create(
            attributes: [
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_nama' => $request->kelurahan_nama,
                'kelurahan_code' => $request->kelurahan_code,
            ]
        );
        //redirect
        if ($status) {
            logActivity('Tambah Kelurahan', 'User berhasil menambah data Kelurahan');
            return redirect()->route('addDataKelurahan')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('addDataKelurahan')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Kelurahan::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Ubah Data Kelurahan';
        $data['detail'] = $detail;
        $data['rs_kec'] = Kecamatan::orderBy('kecamatan_nama')->get();
        return view('master.kelurahan.edit', $data);
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
            'kecamatan_id' => 'required',
            'kelurahan_nama' => 'required',
            'kelurahan_code' => 'required|digits:2',
        ]);
        $detail = Kelurahan::find($id);
        if (empty($detail)) {
            return redirect()->route('dataKelurahan')->with('error', "Data tidak ditemukan");
        }
        // check jika merubah kode
        if ($detail->kelurahan_code != $request->kelurahan_code) {
            $code = Kelurahan::where('kelurahan_code', $request->kelurahan_code)->first();
            if (!empty($code)) {
                return redirect()->route('editDataKelurahan', $id)->with('error', 'Kode Kelurahan sudah ada..')->withInput();
            }
        }
        $detail->kecamatan_id = $request->kecamatan_id;
        $detail->kelurahan_nama = $request->kelurahan_nama;
        $detail->kelurahan_code = $request->kelurahan_code;
        // save
        if ($detail->save()) {
            logActivity('Ubah Kelurahan', 'User berhasil ubah data Kelurahan');
            return redirect()->route('editDataKelurahan', $id)->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('editDataKelurahan', $id)->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Kelurahan::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', "Data tidak ditemukan");
        }
        if ($detail->delete()) {
            logActivity('Hapus Kelurahan', 'User berhasil hapus data Kelurahan');
            return redirect()->back()->with('success', "Data berhasil dihapus");
        } else {
            return redirect()->back()->with('error', "Data gagal dihapus");
        }
    }
}
