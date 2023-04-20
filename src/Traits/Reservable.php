<?php

namespace ShayanYS\LaraReserve\Traits;

use Carbon\Carbon;
use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;
use ShayanYS\LaraReserve\Interfaces\Reserves\CustomerReserveInterface;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Traits\Reserves\ReservableReserve;
use ShayanYS\LaraReserve\Traits\ReservesData\GetReserves;

trait Reservable
{
    use ReservableReserve,GetReserves;

    public function reserves(): MorphMany
    {
        return $this->morphMany(Reserve::class,'reservable');

    }
}
