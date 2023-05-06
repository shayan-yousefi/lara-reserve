# Lara Reserve
![Lara Reserve license](https://img.shields.io/github/license/shayan100/lara-reserve)
![Lara Reserve size](https://img.shields.io/github/languages/code-size/shayan100/lara-reserve)
[![Lara Reserve version](https://img.shields.io/packagist/v/shayanys/lara-reserve)](https://packagist.org/packages/shayanys/lara-reserve)

Lara Reserve Is a Laravel Package To Adds a Reservation feature to the laravel models.

# Installation


To Install Lara Reserve Run Following Command:

```shell
composer require shayanys/lara-reserve
```

and then run Migrations By:

```shell
php artisan migrate
```

# Usage

## Initialize Models to Use Lara Reserve

To Add Lara Reserve Feature To Models, Your Models Should Implement `ReservableInterface` And use `Reservable` Trait.
And the Model Is Ready For Reserve By the Customer. And If Your Model Is a Customer, e.g. User model (Which Can Reserve
Reservables) Should Implement `CustomerInterface` And use `Customer` Trait.

### Example:

#### Reservable Model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Traits\Reservable;

class Book extends Model implements ReservableInterface
{
    use HasFactory, Reservable;
}

```

#### Reservable Model:

```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use ShayanYS\LaraReserve\Interfaces\CustomerInterface;
use ShayanYS\LaraReserve\Traits\Customer;

class User extends Authenticatable implements CustomerInterface
{
    use HasApiTokens, HasFactory, Notifiable, Customer;

}


```

## Reservations

### Call `reserve` Method From Customer

you can reserve a reservable for a customer by reserve method of a customer model:

```php
$reservable = Book::first();
$customer = User::first();

$customer->reserve($reservable,now()->addDay(),'00:00:00',now()->addYear(),'00:00:00',['key' => 'value']);
```

in the above example, `$reservable` will reserve for `$customer`.
#### Parameters
1. reservable you want to reserve for customer
2. the desired date for the reservation
3. the desired time for the reservation in H:i:s format
4. the desired date for the end reservation - **optional**
5. the desired time for the end reservation in H:i:s format - **optional**
6. additional details for the reservation - **optional**

### Call `reserveForCustomer` Method From Reservable

```php
$reservable = Book::first();
$customer = User::first();

$reservable->reserveForCustomer($customer,now()->addDay(),'00:00:00',now()->addYear(),'00:00:00',['code' => 123]);
```

In the above example, like the previous example, `$reservable` will reserve for `$customer`. In the `reserveFroCustomer`

#### Parameters
1. the customer would you like to make a reservation for
2. the desired date for the reservation
3. the desired time for the reservation in H:i:s format
4. the desired date for the end reservation - **optional**
5. the desired time for the end reservation in H:i:s format - **optional**
6. additional details for the reservation - **optional**

### Call `reserveWithoutCustomer` Method From Reservable

```php
$reservable = Book::first();

$reservable->reserveWithoutCustomer(['name' => 'shayan'],now()->addDay(),'00:00:00',now()->addYear(),'00:00:00');
```

With this method, you can reserve a reservable without a customer.
#### Parameters
1. array of the reservation details
2. the desired date for the reservation
3. the desired time for the reservation in H:i:s format
4. the desired date for the end reservation - **optional**
5. the desired time for the end reservation in H:i:s format - **optional**

## Max allowed reserves

### Setting the maximum possible number of reservations on one date and time.

to set maximum allowed reserve in one date you should add `max_allowed_reserves` column to your reservable table in
database:

```php
Schema::table('books', function (Blueprint $table) {
    $table->integer('max_allowed_reserves')->nullable();
});
```

you can set `max_allowed_reserves` column of a reservable by calling `maxAllowedReserves` from reservable:
this method get `$max` to set it as value of `max_allowed_reserves` column.

```php
$tableToReserve = ReseturantTable::first();
$tableToReserve->maxAllowedReserves(5);
```

if you want to get the `max_allowed_reserves` from reservable you can do this:

```php
$tableToReserve = ReseturantTable::first();

$tableToReserve->getMaxAllowedReserves();
//or
$tableToReserve->max_allowed_reserves;
```

returns null if not exists or its null in database.

## Is available

`isAvailable` method can call from reservable and get two arguments date and optional time; and returns is that
reservable available in passed date and time (the time default is 00:00:00).

```php
$airplaneSeat = AirplainSeat::first();

$airplaneSeat->isAvailable(\Carbon\Carbon::createFromFormat('Y-m-d','2023-05-1'),'17:00:00');
```
This code returns true if `max_allowed_reserves` is less than count of all reserves in 2023-05-1 17:00:00; otherwise returns false.

## withoutCheckAvailability and withCheckAvailability
if you don't want to check the availability for some reasons you can use `withoutCheckAvailability` method:
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->withoutCheckAvailability()->reserveForCustomer($customer,now()->addDay(),'00:00:00',['code' => 123]);

//or call reserve method of customer like this:
$customer->reserve($airplaneSeat->withoutCheckAvailability(),now()->addDay(),'00:00:00',['key' => 'value']);
```
this code will bypass the check availability.

also you can set reservable to don't check availability by default:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Traits\Reservable;

class AirplaneSeat extends Model implements ReservableInterface
{
    use HasFactory,Reservable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->checkAvailability = false;
    }
}
```
other way to modify this is to modify `shouldCheckAvailability` method:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ShayanYS\LaraReserve\Interfaces\ReservableInterface;
use ShayanYS\LaraReserve\Models\Reserve;
use ShayanYS\LaraReserve\Traits\Reservable;

class AirplaneSeat extends Model implements ReservableInterface
{
    use HasFactory,Reservable;

    public function shouldCheckAvailability() : bool{
        // TODO: Implement shouldCheckAvailability() method.
        return false;
    }
}
```
this will don't check availability by default. if you want check availability when you set `checkAvailability` property in constructor to false you should do this:
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->withCheckAvailability()->reserveForCustomer($customer,now()->addDay(),'00:00:00',['code' => 123]);

//or call reserve method of customer like this:
$customer->reserve($airplaneSeat->withCheckAvailability(),now()->addDay(),'00:00:00',['key' => 'value']);
```
this will check availability and then reserve.

# Get Reserves
you can get reserves from customer or reservable.
## activeReserves
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->activeReserves()->get(); 
// this will return collection of active reserves which reserved this reservable
//(the reservations that have a reserved date and time or end reservation date and time that are greater than or equal to the current date and time.)

$customer->activeReserves()->get();
// this will return collection of active reserves which reserved by this customer
//(the reservations that have a reserved date and time or end reservation date and time that are greater than or equal to the current date and time.)
```
the `activeReserves` method return a MorphMany relation you can call `get` method to get the collection of reserves; you can also call `paginate` method.

## allReserves
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->allReserves()->get(); 
// this will return collection of all reserves which reserved this reservable

$customer->allReserves()->get();
// this will return collection of all reserves which reserved by this customer
```
the `allReserves` method return a MorphMany relation you can call `get` method to get the collection of reserves; you can also call `paginate` method.

## startedReserves
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->startedReserves()->get(); 
// this will return collection of started reserves which reserved this reservable
//(the reservations that have a reserved date and time that are greater than or equal to the current date and time and end reservation date and time less than current date and time.)
$customer->startedReserves()->get();
// this will return collection of started reserves which reserved by this customer
//(the reservations that have a reserved date and time that are greater than or equal to the current date and time and end reservation date and time less than current date and time.)
```
the `startedReserves` method return a MorphMany relation you can call `get` method to get the collection of reserves; you can also call `paginate` method.

## endedReserves
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->endedReserves()->get(); 
// this will return collection of ended reserves which reserved this reservable
//(the reservations that have a end reservation date and time that are greater than current date and time)
$customer->endedReserves()->get();
// this will return collection of ended reserves which reserved by this customer
//(the reservations that have a end reservation date and time that are greater than current date and time)
```
the `endedReserves` method return a MorphMany relation you can call `get` method to get the collection of reserves; you can also call `paginate` method.

# License

Freely distributable under the terms of the MIT license.

# Buy Me a Cofee

A lot of time has been spent to make this package and we always try to keep it updated. If you like, you can show your support in this way and this is valuable for me.

<a href="http://www.coffeete.ir/shayanys">
       <img src="http://www.coffeete.ir/images/buttons/lemonchiffon.png" style="width:260px;" />
</a>
<br>
<a href="https://www.buymeacoffee.com/shayanys" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" alt="Buy Me A Coffee" style="height: 60px !important;width: 217px !important;" ></a>
