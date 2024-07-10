<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'parent_id',
        'color',
        'serial',
        'inventorie_type',
        'details',
        'value_amount',
        'shade_no',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory_type()
    {
        return $this->belongsTo(Inventorie_type::class, 'inventorie_type');
    }

    public function children()
    {
        return $this->hasMany(Inventory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Inventory::class, 'parent_id');
    }
}
