<?php

namespace ShayanYS\LaraReserve\Traits\Reserves;

use Carbon\Carbon;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use ShayanYS\LaraReserve\Interfaces\Reserves\CustomerReserveInterface;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Traits\LaraReserveDateTimeTrait;

trait ReservableReserve
{
    use LaraReserveDateTimeTrait;

    protected bool $checkAvailability = true;

    public function reserveForCustomer(CustomerReserveInterface $customer, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00',null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null, ?array $metadata = null): Reserve | bool
    {
        return $customer->reserve($this, $reserveDate, $reserveTime, $endReserveDate, $endReserveTime, $metadata);
    }

    public function withoutCheckAvailability():static{
        $this->checkAvailability = false;
        return $this;
    }

    public function withCheckAvailability():static{
        $this->checkAvailability = true;
        return $this;
    }

    public function shouldCheckAvailability():bool{
        return $this->checkAvailability;
    }

    public function isAvailable(DateTimeInterface|DateTime|Carbon $date, DateTimeInterface|DateTime|Carbon|string $time = '00:00:00'): bool
    {

        if (!$this->max_allowed_reserves) {
            return true;

        }

        return $this->getReserveCountInOneDateTime($date, $time) < $this->max_allowed_reserves;
    }

    private function getReserveCountInOneDateTime(DateTimeInterface|DateTime|Carbon $date, string $time = '00:00:00'): int
    {
        $date = $this->createCarbonDateTime($date);
        $reservedCountRow = $this->reserves()->select(DB::raw('count(*) as reserves_count'))->where( 'reserved_time', $time)->whereDate('reserved_date', $date)->first();

        if (!$reservedCountRow) {
            return 0;
        }

        return $reservedCountRow->reserves_count;

    }

    public function maxAllowedReserves(int $max): static
    {
        $this->max_allowed_reserves = $max;
        $this->save();
        return $this;
    }

    public function getMaxAllowedReserves(): int|null
    {
        return $this->max_allowed_reserves;
    }

    public function reserveWithoutCustomer(array $metadata, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00',null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null): Reserve
    {
        return $this->reserves()->create(['reserved_date' => $reserveDate, 'reserved_time' => $reserveTime, 'metadata' => $metadata,'end_reserve_date'=>$endReserveDate,'end_reserve_time'=>$endReserveTime]);
    }
}
