<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'users_id',
        'nama_lengkap',
        'shift',
        'jabatan',
        'no_hp',
        'alamat',
        'tgl_masuk',
    ];

    protected function casts(): array
    {
        return [
            'tgl_masuk' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
