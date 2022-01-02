<?php

namespace Label84\HoursHelper\Tests;

use Label84\HoursHelper\HoursHelperServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            HoursHelperServiceProvider::class,
        ];
    }
}
