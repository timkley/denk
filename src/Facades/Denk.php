<?php

declare(strict_types=1);

namespace Denk\Facades;

use Denk\DenkService;
use Illuminate\Support\Facades\Facade;

class Denk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DenkService::class;
    }
}
