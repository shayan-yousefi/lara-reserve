<?php

namespace ShayanYS\LaraReserve\Traits;

use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Traits\Reserves\CustomerReserve;
use ShayanYS\LaraReserve\Traits\ReservesData\GetReservableFromCustomer;
use ShayanYS\LaraReserve\Traits\ReservesData\GetReserves;

trait Customer
{

    use GetReserves,CustomerReserve;

    public function reserves():MorphMany{
        return $this->morphMany(Reserve::class,'customer');
    }
}
