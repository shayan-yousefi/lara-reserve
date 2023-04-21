<?php
namespace ShayanYS\LaraReserve\Tests\Unit;

use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\TestModels\CustomerTestModel;
use ShayanYS\LaraReserve\TestModels\ReservableTestModel;
use ShayanYS\LaraReserve\Tests\TestCase;

class ReservableTest extends TestCase
{


    /**
     * A basic unit test example.
     */
    public function test_reservable_can_reserve_for_a_customer(): void
    {
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);
        $reserve = $reservable->reserveForCustomer($customer, now(), '00:00:00',['someDetails' =>'details']);

        $this->assertInstanceOf(\ShayanYS\LaraReserve\Models\Reserve::class,$reserve);
        $this->assertEquals($reserve->reserved_date->format('Y-m-d'),now()->format('Y-m-d'));
        $this->assertArrayHasKey('someDetails',$reserve->metadata);

    }
}
