<?php

namespace ShayanYS\LaraReserve\Tests;

use ShayanYS\LaraReserve\LaraReserveServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            LaraReserveServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

        $migrationReserve = include dirname(__DIR__) . '/database/migrations/create_reserves_table.php';
        $migrationReservable = include __DIR__ . '/TestsMigrations/2023_04_22_103322_create__reservable_test_models_table.php';
        $migrationCustomer = include __DIR__ . '/TestsMigrations/2023_04_22_103311_create__customer_test_models_table.php';
        $migrationReserve->up();
        $migrationReservable->up();
        $migrationCustomer->up();
    }
}
