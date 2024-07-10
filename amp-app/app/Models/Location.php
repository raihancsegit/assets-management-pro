<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    public function milk_productions()
    {
        return $this->hasMany(MilkProduction::class)->orderBy('id', 'desc');
    }
}
