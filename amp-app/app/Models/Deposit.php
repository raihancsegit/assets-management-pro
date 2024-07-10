<?php

namespace App\Models;

use App\Traits\Activeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Deposit extends Model
{
    use Activeable,HasFactory;

    protected $fillable = [
        'category_id',
        'type_id',
        'unit_id',
        'details',
        'receipt_no',
        'amount',
        'unit_value',
        'date',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function attachment(): MorphOne
    {
        return $this->morphOne(Attachment::class, 'attachmentable');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function manager()
    {
        return $this->belongsTo(DepositManager::class, 'deposit_id');
    }
}
