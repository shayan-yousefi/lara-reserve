<?php

namespace ShayanYS\LaraReserve\Interfaces\ReservesData;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use PhpParser\Builder;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;

interface GetReservesInterface
{
    public function activeReserves(): MorphMany;
    public function allReserves():  MorphMany;
    public function startedReserves(): MorphMany;
    public function endedReserves(): MorphMany;
}
