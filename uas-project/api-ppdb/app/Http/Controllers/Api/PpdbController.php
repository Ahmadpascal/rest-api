<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ppdb;


class PpdbController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'nisn' => 'required|unique:ppdbs',
            'asal_sekolah' => 'required',
            'jalur' => 'required'
        ]);

        $ppdb = Ppdb::create([
            'user_id' => $request->user()->id,
            'nama_lengkap' => $request->nama_lengkap,
            'nisn' => $request->nisn,
            'asal_sekolah' => $request->asal_sekolah,
            'jalur' => $request->jalur,
        ]);

        $data = Ppdb::with('user')->get();

        return response()->json([
            'message' => 'Pendaftaran PPDB berhasil',
            'total' => $data->count(),
            'data' => $ppdb
        ], 201);
    }

    public function me(Request $request)
    {
        return response()->json(
            $request->user()->ppdb
        );
    }

}
