<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ppdb;
use App\Models\PpdbDocument;
use Illuminate\Validation\Rule;

class PpdbDocumentController extends Controller
{
    public function store(Request $request, Ppdb $ppdb)
    {
        $request->validate([
            'category' => ['required', 'in:wajib,tambahan'],
            'type'     => ['required', 'string'],
            'file'     => [
                'required',
                'file',
                'mimes:' . implode(',', PpdbDocument::ALLOWED_MIMES),
                'max:' . PpdbDocument::MAX_SIZE,
            ],
        ]);

        if ($request->category === 'wajib') {
            $exists = $ppdb->documents()
                ->where('category', 'wajib')
                ->where('type', $request->type)
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    'type' => 'Dokumen ini sudah diupload.',
                ]);
            }
        }

        $path = $request->file('file')->store('ppdb_documents', 'public');

        $document = $ppdb->documents()->create([
            'category'  => $request->category,
            'type' => $request->type,
            'file_path' => $path,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil diupload',
            'data' => $document
        ], 201);
    }
}
