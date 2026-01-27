<?php

declare(strict_types=1);

namespace Denk\Facades;

use Denk\DenkService;
use Denk\DenkServiceProvider;
use Denk\Generators\ImageGenerator;
use Denk\Generators\JsonGenerator;
use Denk\Generators\TextGenerator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static TextGenerator text()
 * @method static JsonGenerator json()
 * @method static ImageGenerator image()
 */
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
