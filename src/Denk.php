<?php

declare(strict_types=1);

namespace Denk;

use Denk\Generators\ImageGenerator;
use Denk\Generators\JsonGenerator;
use Denk\Generators\TextGenerator;

class Denk
{
    public static function text(): TextGenerator
    {
        return new TextGenerator;
    }

    public static function json(): JsonGenerator
    {
        return new JsonGenerator;
    }

    public static function image(): ImageGenerator
    {
        return new ImageGenerator;
    }
}
