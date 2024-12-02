<?php

declare(strict_types=1);

namespace Denk\Facades;

use Denk\DenkService;
use Denk\DenkServiceProvider;
use Illuminate\Support\Facades\Facade;

class Denk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DenkService::class;
    }

    public static function fake(array $responses = []): void
    {
        DenkServiceProvider::fake($responses);
    }
}
