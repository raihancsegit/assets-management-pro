<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProductionCategory extends Model
{
    use HasFactory;

    protected $table = 'milk_production_categories';

    public function milk_productions()
    {
        return $this->hasMany(MilkProduction::class)->orderBy('id', 'desc');
    }
}
