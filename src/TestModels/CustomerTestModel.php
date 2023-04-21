<?php

namespace ShayanYS\LaraReserve\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;
use ShayanYS\LaraReserve\Interfaces\Reserves\CustomerReserveInterface;
use ShayanYS\LaraReserve\Traits\Customer;

class CustomerTestModel extends Model implements CustomerInterface
{
    use HasFactory,Customer;

    protected $fillable = ['id'];
}
