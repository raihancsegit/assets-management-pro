<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventorie_type extends Model
{
    use HasFactory;

    public function inventories()
    {
        return $this->hasMany(Inventory::class)->orderBy('id', 'desc');
    }
}
