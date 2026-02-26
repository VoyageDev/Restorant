<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'meja';

    protected $fillable = [
        'no_meja',
        'status',
        'jumlah_orang',
    ];

    protected $casts = [
        'jumlah_orang' => 'integer',
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'meja_id');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'meja_id');
    }
}
