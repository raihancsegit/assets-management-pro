<?php

namespace App\Traits;

trait Activeable
{
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
