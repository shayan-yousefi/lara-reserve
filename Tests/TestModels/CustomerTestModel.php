<?php

namespace ShayanYS\LaraReserve\Tests\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;
use ShayanYS\LaraReserve\Traits\Customer;

class CustomerTestModel extends Model implements CustomerInterface
{
    use HasFactory,Customer;

    protected $fillable = ['id'];
}
