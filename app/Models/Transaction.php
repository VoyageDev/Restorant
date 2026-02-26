<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'users_id',
        'meja_id',
        'no_trx',
        'waiter_name',
        'order_type',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'transaction_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'transaction_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'transaction_id');
    }
}
