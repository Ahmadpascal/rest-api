<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ppdb;
use App\Models\PpdbDocument; 

class AdminDocumentController extends Controller
{
    public function index(Ppdb $ppdb)
    {
        return response()->json([
            'ppdb' => $ppdb,
            'documents' => $ppdb->documents
        ]);
    }

    public function validateDocument(Request $request, PpdbDocument $document)
    {
        $request->validate([
            'status' => 'required|in:pending,valid,invalid',
            'note' => 'nullable|string'
        ]);

        $document->update([
            'status' => $request->status,
            'note' => $request->note
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil divalidasi',
            'data' => $document
        ]);
    }
}
