<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ppdb;

class PpdbAdminController extends Controller
{
    public function index()
    {
        return response()->json(
            Ppdb::with('user')->get()
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diterima,ditolak',
        ]);

        $ppdb = Ppdb::findOrFail($id);

        $ppdb->update([
            'status' => $request->status,
        ]);
        // $ppdb->status = $request->status;
        // $ppdb->save();

        return response()->json([
            'message' => 'Status pendaftaran berhasil diperbarui',
            'data' => $ppdb['status']
        ]);
    }
}
