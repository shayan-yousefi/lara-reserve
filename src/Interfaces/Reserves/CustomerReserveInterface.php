<?php

namespace  ShayanYS\LaraReserve\Interfaces\Reserves;
use Carbon\Carbon;
use DateTime;
use DateTimeInterface;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Models\Reserve;

interface  CustomerReserveInterface{
    public function reserve(ReservableInterface $reservable, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00',null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null, ?array $metadata = null): Reserve | bool;
}
