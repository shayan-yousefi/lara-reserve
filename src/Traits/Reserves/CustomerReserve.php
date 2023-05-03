<?php

namespace ShayanYS\LaraReserve\Traits\Reserves;

use Carbon\Carbon;
use DateTime;
use DateTimeInterface;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Interfaces\Reserves\CustomerReserveInterface;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Traits\LaraReserveDateTimeTrait;

trait CustomerReserve
{

    use LaraReserveDateTimeTrait;

    public function reserve(ReservableInterface $reservable, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00', null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null, ?array $metadata = null): Reserve|bool
    {
        $reserveDate = $this->createCarbonDateTime($reserveDate);

        if ($reservable->shouldCheckAvailability()) // if reservable is available then do reserve
        {
            return $this->reserveWithCheckAvailability($reservable, $reserveDate, $reserveTime, $endReserveDate, $endReserveTime, $metadata);
        }

        return $this->doReserve($reservable, $reserveDate, $reserveTime, $endReserveDate, $endReserveTime, $metadata);
    }

    private function reserveWithCheckAvailability(ReservableInterface $reservable, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00', null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null, ?array $metadata = null): Reserve|bool
    {
        if ($reservable->isAvailable($reserveDate, $reserveTime)) {// if reservable is available then do reserve
            //call doReserve method to reserve current reservable for this customer
            return $this->doReserve($reservable, $reserveDate, $reserveTime,$endReserveDate, $endReserveTime, $metadata);
        }

        return false;
    }

    private function doReserve(ReservableInterface $reservable, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00', null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null, ?array $metadata = null): Reserve
    {
        $reserve = $this->reserves()->make(['reserved_date' => $reserveDate->toDateString(), 'reserved_time' => $reserveTime, 'metadata' => $metadata,'end_reserve_date' => $endReserveDate, 'end_reserve_time' => $endReserveTime])->reservable()->associate($reservable); // rserve a reservable for a customer

        $reserve->save();
        return $reserve;
    }


}
