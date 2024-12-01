<?php

namespace Tests;

use Denk\DenkServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            DenkServiceProvider::class,
        ];
    }
}
