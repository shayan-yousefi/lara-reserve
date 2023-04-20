<?php
namespace ShayanYS\LaraReserve\Interfaces;


use ShayanYS\LaraReserve\Interfaces\Reserves\ReservableReserveInterface;
use ShayanYS\LaraReserve\Interfaces\ReservesData\GetCustomersFromReservableInterface;
use ShayanYS\LaraReserve\Interfaces\ReservesData\GetReservesInterface;

interface ReservableInterface extends ReservableReserveInterface,GetReservesInterface,ReservesRelationshipInterface {



}
