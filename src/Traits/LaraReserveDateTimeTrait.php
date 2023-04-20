<?php

namespace ShayanYS\LaraReserve\Traits;

use Carbon\Carbon;
use DateTime;
use DateTimeInterface;

trait LaraReserveDateTimeTrait
{
    private function createCarbonDateTime(DateTimeInterface $date): DateTime
    {

        if(!$date instanceof Carbon){
            return Carbon::createFromInterface($date);
        }

        return $date;
    }
}
