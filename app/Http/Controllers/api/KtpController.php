<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Ktp;
use Illuminate\Http\Request;

class KtpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ktp = Ktp::with(['provinsi', 'kabupaten', 'kecamatan', 'kelurahan'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $ktp
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
