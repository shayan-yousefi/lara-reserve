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

        $migration = include dirname(__DIR__) . '/database/migrations/create_reserves_table.php';
        $migration->up();
    }
}
