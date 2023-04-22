<?php

namespace ShayanYS\LaraReserve\Tests\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Traits\Reservable;

class ReservableTestModel extends Model implements ReservableInterface
{
    use HasFactory,Reservable;
    protected $fillable = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
//        $this->checkAvailability = false;
    }
}
