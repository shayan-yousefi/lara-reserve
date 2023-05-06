<?php

namespace ShayanYS\LaraReserve\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;

class Reserve extends Model
{
    use HasFactory;

    protected $fillable = ['reserved_date', 'metadata', 'reserved_time','end_reserve_date','end_reserve_time'];
    protected $casts = ['metadata' => 'array', 'reserved_date' => 'date','end_reserve_date' => 'date'];

    public function reservable(): MorphTo
    {
        return $this->morphTo();
    }

    public function customer(): MorphTo
    {
        return $this->morphTo('customer', 'customer_type', 'customer_id');
    }

    public function scopeActiveReserves(Builder $query)
    {
        return $query->with('customer')->where('reserved_date', '>=', now());
    }
}
