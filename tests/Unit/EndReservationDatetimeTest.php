<?php

namespace ShayanYS\LaraReserve\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Tests\TestCase;
use ShayanYS\LaraReserve\Tests\TestModels\CustomerTestModel;
use ShayanYS\LaraReserve\Tests\TestModels\ReservableTestModel;

class EndReservationDatetimeTest extends TestCase
{
    public function test_reservable_can_reserve_for_a_customer_with_end_reserve_dattime(): void
    {
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);
        $reserve = $reservable->reserveForCustomer($customer, now(), '00:00:00',now()->addYear(),'00:00:00', ['someDetails' => 'details']);

        $this->assertInstanceOf(Reserve::class, $reserve);
        $this->assertEquals($reserve->reserved_date->format('Y-m-d'), now()->format('Y-m-d'));
        $this->assertEquals($reserve->end_reserve_date->format('Y-m-d'), now()->addYear()->format('Y-m-d'));
        $this->assertEquals($reserve->id, $customer->startedReserves()->get()[0]->id);
        $this->assertArrayHasKey('someDetails', $reserve->metadata);

    }
}
