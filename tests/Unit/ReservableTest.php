<?php

namespace ShayanYS\LaraReserve\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Tests\TestCase;
use ShayanYS\LaraReserve\Tests\TestModels\CustomerTestModel;
use ShayanYS\LaraReserve\Tests\TestModels\ReservableTestModel;

class ReservableTest extends TestCase
{


    /**
     * A basic unit test example.
     */
    public function test_reservable_can_reserve_for_a_customer(): void
    {
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);
        $reserve = $reservable->reserveForCustomer($customer, now(), '00:00:00',metadata: ['someDetails' => 'details']);

        $this->assertInstanceOf(Reserve::class, $reserve);
        $this->assertEquals($reserve->reserved_date->format('Y-m-d'), now()->format('Y-m-d'));
        $this->assertArrayHasKey('someDetails', $reserve->metadata);

    }

    public function test_reservable_with_fully_reserved_can_reserve_again(): void
    {
        $reservable = ReservableTestModel::make(['id' => 1])->withCheckAvailability();
        $customer = CustomerTestModel::make(['id' => 1]);

        $reservable->max_allowed_reserves = 5;
        for ($i = 0; $i < 5; $i++) {
            $reservable->reserveForCustomer($customer, Carbon::createFromFormat('Y-m-d', '2023-04-22'), '00:00:00',metadata: ['someDetails' => 'details']);
            // 5 reserves filled in this date and time with this loop
        }
        $reserve = $reservable->reserveForCustomer($customer, Carbon::createFromFormat('Y-m-d', '2023-04-22'), '00:00:00',metadata: ['someDetails' => 'details']);
        //this is 6th reserve for this time and time, this shouldn't be reserve and return false

        $this->assertFalse($reserve);
        $this->assertFalse($reservable->isAvailable(Carbon::createFromFormat('Y-m-d', '2023-04-22')));
    }

    public function test_without_check_availability_is_working(){
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);

        $reservable->max_allowed_reserves = 5;
        for ($i = 0; $i < 5; $i++) {
            $reservable->withoutCheckAvailability()->reserveForCustomer($customer, Carbon::createFromFormat('Y-m-d', '2023-04-22'), '00:00:00', metadata: ['someDetails' => 'details']);
            // 5 reserves filled in this date and time with this loop
        }
        $reserve = $reservable->withoutCheckAvailability()->reserveForCustomer($customer, Carbon::createFromFormat('Y-m-d', '2023-04-22'), '00:00:00', metadata: ['someDetails' => 'details']);
        //this is 6th reserve for this time and time, this should be reserve because use of withoutCheckAvailability

        $this->assertInstanceOf(Reserve::class, $reserve);
        $this->assertFalse($reservable->isAvailable(Carbon::createFromFormat('Y-m-d', '2023-04-22')));


    }

    public function test_with_check_availability_is_working(){
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);

        $reservable->max_allowed_reserves = 5;
        for ($i = 0; $i < 5; $i++) {
            $reservable->withCheckAvailability()->reserveForCustomer($customer, Carbon::createFromFormat('Y-m-d', '2023-04-22'), '00:00:00', metadata: ['someDetails' => 'details']);
            // 5 reserves filled in this date and time with this loop
        }
        $reserve = $reservable->withCheckAvailability()->reserveForCustomer($customer, Carbon::createFromFormat('Y-m-d', '2023-04-22'), '00:00:00', metadata: ['someDetails' => 'details']);
        //this is 6th reserve for this time and time, this should be reserve because use of withoutCheckAvailability

        $this->assertFalse($reserve);
        $this->assertFalse($reservable->isAvailable(Carbon::createFromFormat('Y-m-d', '2023-04-22')));


    }

    public function test_reserve_without_customer(){
        $reservable = ReservableTestModel::make(['id' => 1]);

        $reserve = $reservable->reserveWithoutCustomer(['name' => 'shayan','email'=> 'example@email.com'],now(), '00:00:00');

        $this->assertInstanceOf(Reserve::class, $reserve);
        $this->assertEquals($reserve->reserved_date->format('Y-m-d'), now()->format('Y-m-d'));
        $this->assertArrayHasKey('name', $reserve->metadata);
        $this->assertArrayHasKey('email', $reserve->metadata);
        $this->assertEquals($reserve->metadata, ['name' => 'shayan','email'=> 'example@email.com']);
    }

    public function test_active_reserves_method_is_working(){
        $reservable = ReservableTestModel::make(['id' => 1]);
        $customer = CustomerTestModel::make(['id' => 1]);

        $reservable->reserveForCustomer($customer, now()->subDay(), '00:00:00');
        $reservable->reserveForCustomer($customer, now()->addYear(), '00:00:00');
        $reservable->reserveForCustomer($customer, now()->addDay(), '00:00:00');

        $activeReservesFromReservable = $reservable->activeReserves();
        $allReservesFromReservable = $reservable->allReserves();

        $activeReservesFromCustomer = $customer->activeReserves();
        $allReservesFromCustomer = $customer->allReserves();

        $this->assertInstanceOf(MorphMany::class,$activeReservesFromCustomer);
        $this->assertInstanceOf(MorphMany::class,$allReservesFromCustomer);

        $this->assertInstanceOf(MorphMany::class,$activeReservesFromReservable);
        $this->assertInstanceOf(MorphMany::class,$allReservesFromReservable);

        $this->assertCount(2,$activeReservesFromCustomer->get());
        $this->assertCount(3,$allReservesFromCustomer->get());

        $this->assertCount(2,$activeReservesFromReservable->get());
        $this->assertCount(3,$allReservesFromReservable->get());
    }
}
