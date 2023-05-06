<?php

namespace ShayanYS\LaraReserve\Interfaces\Reserves;

use Carbon\Carbon;
use DateTime;
use DateTimeInterface;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;
use ShayanYS\LaraReserve\Models\Reserve;

interface ReservableReserveInterface
{
    public function reserveForCustomer(CustomerInterface $customer, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00',null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null, ?array $metadata = null): Reserve|bool;

    public function isAvailable(DateTimeInterface|DateTime|Carbon $date, DateTimeInterface|DateTime|Carbon|string $time = '00:00:00'): bool;

    public function maxAllowedReserves(int $max): static;

    public function getMaxAllowedReserves(): int|null;

    public function reserveWithoutCustomer(array $metadata, DatetimeInterface|Carbon|DateTime $reserveDate, string $reserveTime = '00:00:00',null|DatetimeInterface|Carbon|DateTime $endReserveDate = null, ?string $endReserveTime = null): Reserve;

    public function withoutCheckAvailability(): static;

    public function withCheckAvailability(): static;

    public function shouldCheckAvailability(): bool;
}
