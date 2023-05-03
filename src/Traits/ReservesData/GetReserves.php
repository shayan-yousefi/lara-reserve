<?php

namespace ShayanYS\LaraReserve\Traits\ReservesData;

use Illuminate\Database\Eloquent\Relations\MorphMany;


trait GetReserves
{

    public function activeReserves(): MorphMany
    {
        return $this->reserves()->whereDate('reserved_date', '>', now())->orWhere(function ($query) {
                $query->whereDate('reserved_date', '=', now())->whereTime('reserved_time', '>=', now());
            })->orWhereDate('end_reserve_date', '>', now())->orWhere(function ($query) {
                $query->whereDate('end_reserve_date', '=', now())->whereTime('end_reserve_time', '>=', now());
            })->with(['customer', 'reservable']);
    }

    public function allReserves(): MorphMany
    {
        return $this->reserves()->with(['customer', 'reservable']);
    }

    public function startedReserves(): MorphMany{
        return $this->reserves()->whereDate('reserved_date', '<', now())->orWhere(function ($query) {
                $query->whereDate('reserved_date', '=', now())->whereTime('reserved_time', '<=', now());
            })->WhereDate('end_reserve_date', '>', now())->orWhere(function ($query) {
            $query->whereDate('end_reserve_date', '=', now())->whereTime('end_reserve_time', '>=', now());
        })->with(['customer', 'reservable']);
    }

    public function endedReserves(): MorphMany{
        return $this->reserves()->whereDate('end_reserve_date', '<', now())->orWhere(function ($query) {
            $query->whereDate('end_reserve_date', '=', now())->whereTime('reserved_time', '<=', now());
        });
    }

}
