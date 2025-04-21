<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Ktp;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['title'] = 'Data Pekerjaan';
        // pencarian
        $pkrjn = Pekerjaan::orderBy('pekerjaan_nama', 'ASC');
        if ($request->filled('pekerjaan')) {
            $pkrjn->where('pekerjaan_nama', 'LIKE', '%' . $request->pekerjaan . '%');
        }
        $pekerjaan = $pkrjn->paginate(50)->withQueryString();
        // end pencarian
        $data['rs_datas'] = $pekerjaan;
        return view('master.pekerjaan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Data Pekerjaan';
        return view('master.pekerjaan.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pekerjaan_nama' => 'required',
        ]);
        $status = Pekerjaan::create(
            [
                'pekerjaan_nama' => $request->pekerjaan_nama,
            ]
        );
        //redirect
        if ($status) {
            logActivity('Tambah Pekerjaan', 'User berhasil menambah data pekerjaan');
            return redirect()->route('addDataPekerjaan')->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('addDataPekerjaan')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Pekerjaan::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        $data['title'] = 'Ubah Data Pekerjaan';
        $data['detail'] = $detail;
        return view('master.pekerjaan.edit', $data);
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
            'pekerjaan_nama' => 'required',
        ]);
        $detail = Pekerjaan::find($id);
        if (empty($detail)) {
            return redirect()->route('dataPekerjaan')->with('error', "Data tidak ditemukan");
        }
        $detail->pekerjaan_nama = $request->pekerjaan_nama;
        // save
        if ($detail->save()) {
            logActivity('Ubah Pekerjaan', 'User berhasil ubah data pekerjaan');
            return redirect()->route('editDataPekerjaan', $id)->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->route('editDataPekerjaan', $id)->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detail = Pekerjaan::find($id);
        if (empty($detail)) {
            return redirect()->back()->with('error', "Data tidak ditemukan");
        }
        // cek data digunakan / tidak
        $stPekerjaan = Ktp::where('pekerjaan_id', $id)->count();
        if ($stPekerjaan > 0) {
            return redirect()->back()->with('error', "Data digunakan untuk data ktp");
        }
        if ($detail->delete()) {
            logActivity('Hapus Pekerjaan', 'User berhasil hapus data Pekerjaan');
            return redirect()->back()->with('success', "Data berhasil dihapus");
        } else {
            return redirect()->back()->with('error', "Data gagal dihapus");
        }
    }
}
