<?php

namespace ShayanYS\LaraReserve\Traits\ReservesData;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

trait GetReserves
{

    public function activeReserves(): MorphMany
    {
        return $this->reserves()->whereDate('reserved_date', '>', now())->orWhere(function ($query){
            $query->whereDate('reserved_date', '=', now())->whereTime('reserved_time','>=', now());
        })->with(['customer', 'reservable']);
    }

    public function allReserves(): MorphMany
    {
        return $this->reserves()->with(['customer', 'reservable']);
    }

}
