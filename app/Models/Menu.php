<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'kategori_menu_id',
        'name',
        'price',
        'stock',
        'daily_stock',
        'status',
    ];

    protected $casts = [
        'stock' => 'integer',
        'daily_stock' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_menu_id');
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'menu_id');
    }
}
