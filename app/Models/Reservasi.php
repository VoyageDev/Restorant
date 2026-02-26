<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasis';

    protected $fillable = [
        'meja_id',
        'nama_pelanggan',
        'no_telepon',
        'waktu_reservasi',
        'status',
    ];

    protected $casts = [
        'waktu_reservasi' => 'datetime',
    ];

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }
}
