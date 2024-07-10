<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProduction extends Model
{
    use HasFactory;

    protected $table = 'milk_productions';

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $fillable = [
        'date',
        'category_id',
        'quantity',
        'sell_price',
        'location_id',
        'comments',
    ];

    public function milk_category()
    {
        return $this->belongsTo(MilkProductionCategory::class, 'category_id');
    }

    public function milk_location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
