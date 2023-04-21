<?php

namespace ShayanYS\LaraReserve\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Interfaces\Reserves\CustomerReserveInterface;
use ShayanYS\LaraReserve\Traits\Reservable;

class ReservableTestModel extends Model implements ReservableInterface
{
    use HasFactory,Reservable;
    protected $fillable = ['id'];

}
