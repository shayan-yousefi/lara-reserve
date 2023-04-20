<?php
namespace ShayanYS\LaraReserve\Interfaces;

use ShayanYS\LaraReserve\Interfaces\Reserves\CustomerReserveInterface;
use ShayanYS\LaraReserve\Interfaces\ReservesData\GetReservablesFromCustomerInterface;
use ShayanYS\LaraReserve\Interfaces\ReservesData\GetReservesInterface;

interface CustomerInterface extends CustomerReserveInterface,GetReservesInterface,ReservesRelationshipInterface {

}
