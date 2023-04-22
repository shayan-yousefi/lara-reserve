# Lara Reserve
___
Lara Reserve Is a Laravel Package To Adds a Reservation feature to the laravel models.

# Installation
___
To Install Lara Reserve Run Following Command:
```shell
composer require shayanys/lara-reserve
```

# Usage

## Initialize Models to Use Lara Reserve
To Add Lara Reserve Feature To Models, Your Models Should Implement `ReservableInterface` And use `Reservable` Trait. And the Model Is Ready For Reserve By the Customer. And If Your Model Is a Customer, e.g. User model (Which Can Reserve Reservables) Should Implement `CustomerInterface` And use `Customer` Trait.

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
