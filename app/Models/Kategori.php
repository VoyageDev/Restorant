<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori_menu';

    protected $fillable = [
        'name',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'kategori_menu_id');
    }
}
