<?php

namespace App\Models;

use App\Traits\Activeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Activeable,HasFactory;

    protected $fillable = [
        'name',
        'details',
        'has_inventory',
        'parent_id',
        'icon',
    ];

    public function types()
    {
        return $this->hasMany(Type::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', 1)->orderBy('id', 'desc');

    }

    public function totalValue()
    {
        return $this->deposits()->sum('amount');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function total_deposits()
    {
        return $this->deposits()
            ->selectRaw('category_id, sum(amount) as total_amount')
            ->groupBy('category_id');
    }

    public function expanses()
    {
        return $this->hasMany(Expanse::class)->where('status', 1)->orderBy('id', 'desc');

    }

    public function incomes()
    {
        return $this->hasMany(Income::class)->where('status', 1)->orderBy('id', 'desc');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class)->orderBy('id', 'desc');
    }

    public function breadings()
    {
        return $this->hasMany(Inventory::class)->whereNotNull('parent_id')->orderBy('id', 'desc');
    }
}
