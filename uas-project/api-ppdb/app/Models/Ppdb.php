<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ppdb extends Model
{
    const STATUS_PENDING  = 'pending';
    const STATUS_DITERIMA = 'diterima';
    const STATUS_DITOLAK  = 'ditolak';
    
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nisn',
        'asal_sekolah',
        'jalur',
        'status'
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(PpdbDocument::class);
    }
}
