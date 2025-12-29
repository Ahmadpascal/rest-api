<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbDocument extends Model
{
    use HasFactory;

    protected $table = 'documents';

    public const TYPES = [
        'kk'      => 'Kartu Keluarga',
        'ijazah'  => 'Ijazah',
        'akta'    => 'Akta Kelahiran',
        'pas_foto' => 'Pas Foto',
    ];

    public const ALLOWED_MIMES = ['pdf'];
    public const MAX_SIZE = 2048;

    protected $fillable = [
        'ppdb_id',
        'category',
        'type',
        'file_path',
        'status',
        'note',
    ];

    /**
     * Dokumen milik satu PPDB
     */
    public function ppdb()
    {
        return $this->belongsTo(Ppdb::class);
    }
}
