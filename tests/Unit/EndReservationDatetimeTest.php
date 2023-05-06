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
        $reserveFromCustomer = $customer->reserve($reservable, now(), '00:00:00',now()->addYear(),'00:00:00', ['someDetails' => 'details']);

        $this->assertInstanceOf(Reserve::class, $reserve);
        $this->assertEquals($reserve->reserved_date->format('Y-m-d'), now()->format('Y-m-d'));
        $this->assertEquals($reserve->end_reserve_date->format('Y-m-d'), now()->addYear()->format('Y-m-d'));
        $this->assertEquals($reserve->id, $customer->startedReserves()->get()[0]->id);
        $this->assertArrayHasKey('someDetails', $reserve->metadata);

        $this->assertInstanceOf(Reserve::class, $reserveFromCustomer);
        $this->assertEquals($reserveFromCustomer->reserved_date->format('Y-m-d'), now()->format('Y-m-d'));
        $this->assertEquals($reserveFromCustomer->end_reserve_date->format('Y-m-d'), now()->addYear()->format('Y-m-d'));
        $this->assertEquals($reserveFromCustomer->id, $customer->startedReserves()->get()[1]->id);
        $this->assertArrayHasKey('someDetails', $reserveFromCustomer->metadata);

    }

    public function test_reserve_without_customer_with_end_datetime(): void
    {
        $reservable = ReservableTestModel::make(['id' => 1]);

        $reserve = $reservable->reserveWithoutCustomer(['name' => 'shayan','email'=> 'example@email.com'],now(), '00:00:00',now()->addYear(),'00:00:00');

        $this->assertInstanceOf(Reserve::class, $reserve);
        $this->assertEquals($reserve->reserved_date->format('Y-m-d'), now()->format('Y-m-d'));
        $this->assertEquals($reserve->end_reserve_date->format('Y-m-d'), now()->addYear()->format('Y-m-d'));
        $this->assertEquals($reserve->end_reserve_time, '00:00:00');
        $this->assertArrayHasKey('name', $reserve->metadata);
        $this->assertArrayHasKey('email', $reserve->metadata);
        $this->assertEquals($reserve->metadata, ['name' => 'shayan','email'=> 'example@email.com']);

    }

    public function test_active_reserves_method_is_working(){
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);

        $reservable->reserveForCustomer($customer, now()->subDay(), '00:00:00',now()->addYear(),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->addYear(), '00:00:00',now()->addYears(2),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->addDay(), '00:00:00',now()->addYear(),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->subYears(2), '00:00:00',now()->subYears(1),'00:00:00');

        $customer->reserve($reservable, now()->subDay(), '00:00:00',now()->addYear(),'00:00:00');
        $customer->reserve($reservable, now()->addYear(), '00:00:00',now()->addYears(2),'00:00:00');
        $customer->reserve($reservable, now()->addDay(), '00:00:00',now()->addYear(),'00:00:00');
        $customer->reserve($reservable, now()->subYears(2), '00:00:00',now()->subYears(1),'00:00:00');

        $activeReservesFromReservable = $reservable->activeReserves();
        $activeReservesFromCustomer = $customer->activeReserves();
        $this->assertInstanceOf(MorphMany::class,$activeReservesFromCustomer);
        $this->assertInstanceOf(MorphMany::class,$activeReservesFromReservable);
        $this->assertCount(6,$activeReservesFromCustomer->get());
        $this->assertCount(6,$activeReservesFromReservable->get());

    }

    public function test_started_reserves_method_is_working(){
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);

        $reservable->reserveForCustomer($customer, now()->subDay(), '00:00:00',now()->addYear(),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->addYear(), '00:00:00',now()->addYears(2),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->addDay(), '00:00:00',now()->addYear(),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->subYears(2), '00:00:00',now()->subYears(1),'00:00:00');
        $reservable->reserveForCustomer($customer, now(), '00:00:00',now()->subYears(5),'00:00:00');

        $customer->reserve($reservable, now()->subDay(), '00:00:00',now()->addYear(),'00:00:00');
        $customer->reserve($reservable, now()->addYear(), '00:00:00',now()->addYears(2),'00:00:00');
        $customer->reserve($reservable, now()->addDay(), '00:00:00',now()->addYear(),'00:00:00');
        $customer->reserve($reservable, now()->subYears(2), '00:00:00',now()->subYears(1),'00:00:00');
        $customer->reserve($reservable, now(), '00:00:00',now()->subYears(5),'00:00:00');

        $activeReservesFromReservable = $reservable->startedReserves();
        $activeReservesFromCustomer = $customer->startedReserves();
        $this->assertInstanceOf(MorphMany::class,$activeReservesFromCustomer);
        $this->assertInstanceOf(MorphMany::class,$activeReservesFromReservable);
        $this->assertCount(2,$activeReservesFromCustomer->get());
        $this->assertCount(2,$activeReservesFromReservable->get());

    }

    public function test_ended_reserves_method_is_working(){
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);

        $reservable->reserveForCustomer($customer, now()->subDay(), '00:00:00',now()->addYear(),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->addYear(), '00:00:00',now()->addYears(2),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->addDay(), '00:00:00',now()->addYear(),'00:00:00');
        $reservable->reserveForCustomer($customer, now()->subYears(2), '00:00:00',now()->subYears(1),'00:00:00');
        $reservable->reserveForCustomer($customer, now(), '00:00:00',now()->subYears(5),'00:00:00');

        $customer->reserve($reservable, now()->subDay(), '00:00:00',now()->addYear(),'00:00:00');
        $customer->reserve($reservable, now()->addYear(), '00:00:00',now()->addYears(2),'00:00:00');
        $customer->reserve($reservable, now()->addDay(), '00:00:00',now()->addYear(),'00:00:00');
        $customer->reserve($reservable, now()->subYears(2), '00:00:00',now()->subYears(1),'00:00:00');
        $customer->reserve($reservable, now(), '00:00:00',now()->subYears(5),'00:00:00');

        $activeReservesFromReservable = $reservable->endedReserves();
        $activeReservesFromCustomer = $customer->endedReserves();
        $this->assertInstanceOf(MorphMany::class,$activeReservesFromCustomer);
        $this->assertInstanceOf(MorphMany::class,$activeReservesFromReservable);
        $this->assertCount(4,$activeReservesFromCustomer->get());
        $this->assertCount(4,$activeReservesFromReservable->get());

    }
}
