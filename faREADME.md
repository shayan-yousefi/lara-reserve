# Lara Reserve
![Lara Reserve license](https://img.shields.io/github/license/shayan100/lara-reserve)
![Lara Reserve size](https://img.shields.io/github/languages/code-size/shayan100/lara-reserve)
[![Lara Reserve version](https://img.shields.io/packagist/v/shayanys/lara-reserve)](https://packagist.org/packages/shayanys/lara-reserve)

پکیج Lara Reserve قابلیت رزرواسیون رو به مدل های لاراول اضافه میکنه.

for english document version click [here](faREADME.md)

# نحوه نصب


برای نصب Lara Reserve دستور زیر رو اجرا کنید:

```shell
composer require shayanys/lara-reserve
```

و بعد مایگریشن هارو اجرا کنید:

```shell
php artisan migrate
```

# نحوه استفاده

## آماده سازی مدل ها برای استفاده از Lara Reserve

برای اضافه کردن حالت رزرو شوندگی (مدل هایی که قابل رزرو هستند) باید از اینترفیس `ReservableInterface` در مدل مورد نظر پیروی کنید همچنین از تریت (trait) `Reservable` هم استفاده کنید و برای مدل هایی که قابلیت رزرو کردن داردند اینترفیس `CustomerInterface` و تریت `Customer` استفاده کنید.

### مثال:

#### مدل های reservable یا قابل رزرو:

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

#### مدل های Customer یا مشتری:

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

## رزرواسیون

### متد `reserve` در مدل های Customer

شما میتوانید یک reservable را با متد reserve در Customer ها برای یک Customer رزرو کنید:

```php
$reservable = Book::first();
$customer = User::first();

$customer->reserve($reservable,now()->addDay(),'00:00:00',['key' => 'value']);
```

در مثال بالا `reservable$` را برای `customer$` رزرو میکنیم در متد `reserve` اولین پارامتری که به متد پاس میدهید باید موردی باشد که میخواهید برای customer رزرو کنید و حتما باید از interface و trait های مربوط به reservable ها استفاده کنه، پارامتر دوم تاریخی هست که میخواهید برای رزرو ثبت کنید، پارامتر سوم زمانی که میخواهید برای رزرو ثبت کنید با فرمت H:i:s به صورت پیشفرض `00:00:00` میباشد و پارامتر چهارم، اطلاعات اضافی برای رزرو را میتوانید در پارامتر چهارم به صورت یک آرایه پاس بدهید

### متد `reserveForCustomer` در مدل های reservable

```php
$reservable = Book::first();
$customer = User::first();

$reservable->reserveForCustomer($customer,now()->addDay(),'00:00:00',['code' => 123]);
```

در مثال بالا مثل مثال قبلی `reservable$` برای `customer$` رزرو میشود و تنها تفاوت با متد قبلی این هست که در پارامتر اول باید customer پاس داده شود و حتما باید از interface و trait های customer ها استفاده کند

### متد `reserveWithoutCustomer` در مدل های reservable

```php
$reservable = Book::first();

$reservable->reserveWithoutCustomer(['name' => 'shayan'],now()->addDay(),'00:00:00');
```

با متد `reserveWithoutCustomer` میتوانید یک رزرو بدون customer بسازید در این متد پارامتر اول اطلاعات رزرو هست و پارامتر های دوم و سوم تاریخ و زمان رزرو را مشخص میکنند

## بیشترین حد قابل رزرو

### تنظیم کنید که در یک تاریخ و زمان چند رزرو قابل ثبت هست

برای تعیین بیشترین حد مجاز رزرو برای یک reservable باید `max_allowed_reserves` را به جدول ان resrvable در پایگاه داده اضافه کنید:

```php
Schema::table('books', function (Blueprint $table) {
    $table->integer('max_allowed_reserves')->nullable();
});
```

برای مقدار دهی `max_allowed_reserves` در جدول میتوانید از متد `maxAllowedReserves` بر روی یک reservable استفاده کنید:
این متد یک پارامتر دریافت میکند که مقدار `max_allowed_reserves` را برابر آن قرار دهد

```php
$tableToReserve = ReseturantTable::first();
$tableToReserve->maxAllowedReserves(5);
```

اگر میخواهید مقدار مجاز رزرو یک reservable را دریافت کنید میتوانید به شکل زیر عمل کنید.

```php
$tableToReserve = ReseturantTable::first();

$tableToReserve->getMaxAllowedReserves();
//or
$tableToReserve->max_allowed_reserves;
```

اگر این فیلد در پایگاه داده ثبت نشده باشد یا null باشد مثال بالا null بر میگرداند.

## Is available

متد `isAvailable` از روی یک reservable صدا زده میشود تا نشان دهد آن reservable موجود هست یا خیر بر اساس `max_allowed_reserves`. این متد به عنوان پارامتر اول یک تاریخ و به عنوان پارامتر دوم یک زمان دریافت میکند که به صورت پیشفرض 00:00:00 میباشد. و نشان میدهد در اون زمان و تاریخ ایا این reservable قابل رزرو هست یا خیر.

```php
$airplaneSeat = AirplainSeat::first();

$airplaneSeat->isAvailable(\Carbon\Carbon::createFromFormat('Y-m-d','2023-05-1'),'17:00:00');
```
این متد `true` بر میگرداند اگر `max_allowed_reserves` کمتر از تعداد کل رزرو ها در تاریخ و زمان: `2023-05-1 17:00:00` باشد در غیر این صورت false بر میگردد

##  متد های `withCheckAvailability` و `withoutCheckAvailability`
اگر به دلایلی نمیخواهید موجود بودن یک reservable برسی شود و در هر شرایظی رزرو ثبت شود میتوانید از متد `withCheckAvailability` استفاده کنید:
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->withoutCheckAvailability()->reserveForCustomer($customer,now()->addDay(),'00:00:00',['code' => 123]);

//or call reserve method of customer like this:
$customer->reserve($airplaneSeat->withoutCheckAvailability(),now()->addDay(),'00:00:00',['key' => 'value']);
```
این کد برسی کردن موجودیت را دور میزند.

همینطور شما میتوانید کاری کنید تا یک reservable به صوزت پیش فرض موجود بودن را برسی نکند:
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
روش دیگر برای انجام عملیات بالا ویرایش متد `shouldCheckAvailability` در reservable میباشد:
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
اگر زمانی یک reservable را طوری برنامه ریزی کردید تا موجودی را برسی نکند اما بنا به دلایلی خواستید قسمتی از برنامه موجودیت برسی بشود میتوانید از متد `withCheckAvailability` استفاده کنید:
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->withCheckAvailability()->reserveForCustomer($customer,now()->addDay(),'00:00:00',['code' => 123]);

//or call reserve method of customer like this:
$customer->reserve($airplaneSeat->withCheckAvailability(),now()->addDay(),'00:00:00',['key' => 'value']);
```
این کد موجود بودن را چک میکند و اگر reservable مجود بود آن را رزرو میکند.

# دریافت رزرو ها
شما میتوانید رزرو ها را از reservable ها یا customer ها دریافت کنید:
## activeReserves
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->activeReserves()->get(); 
// این کد تمامی رزرو هایی را بر میگرداند که این مورد در ان ها رزرو شده باشد.
//(رزرو هایی که تاریخ و زمان شان نگذشته باشد)

$customer->activeReserves()->get();
// این کد تمامی رزرو هایی را بر میگرداند که توسط این مشتری رزرو شده باشد.
//(رزرو هایی که تاریخ و زمان شان نگذشته باشد)
```
متد `activeReserves` یک MorphMany بر میگرداند که با استفاده از متد `get` میتوانید تمام رزرو ها را دریافت کنید همینطور میتوانید متد `paginate` را روی اون صدا بزنید
## allReserves
```php
$airplaneSeat = AirplainSeat::first();
$customer = User::first();

$airplaneSeat->allReserves()->get(); 
// این کد تمامی رزرو هایی را بر میگرداند که این مورد در ان ها رزرو شده باشد.

$customer->allReserves()->get();
// این کد تمامی رزرو هایی را بر میگرداند که توسط این مشتری رزرو شده باشد.
```
متد `allReserves` یک MorphMany بر میگرداند که با استفاده از متد `get` میتوانید تمام رزرو ها را دریافت کنید همینطور میتوانید متد `paginate` را روی اون صدا بزنید

# License

به صورت رایگان تحت شرایط مجوز MIT توزیع می شود.

# برام یه قهوه بخر

برای ساخته شدن این پکیج زمان بسیاری گذاشته شده و همیشه سعی میشه تا آپدیت بمونه شما اگر دوست داشتید میتونید از این طریق حمایت خودتون رو نشون بدید و این برای من با ارزشه.

<a href="http://www.coffeete.ir/shayanys">
       <img src="http://www.coffeete.ir/images/buttons/lemonchiffon.png" style="width:260px;" />
</a>
<br>
<a href="https://www.buymeacoffee.com/shayanys" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-yellow.png" alt="Buy Me A Coffee" style="height: 60px !important;width: 217px !important;" ></a>
