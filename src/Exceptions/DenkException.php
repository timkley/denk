<?php

declare(strict_types=1);

namespace Denk\Exceptions;

use Exception;

class DenkException extends Exception
{
    public static function noMessages(): DenkException
    {
        return new self('You need to provide at least one message. Set either a `prompt()` or add `messages()`.');
    }

    public static function noPrompt(): DenkException
    {
        return new self('You need to provide a prompt.');
    }

    public static function openAiError(string $message): DenkException
    {
        return new self($message);
    }

    public static function invalidModel(string $model): DenkException
    {
        return new self("Invalid model: $model.");
    }

    public static function noJsonModel(): DenkException
    {
        return new self('You need to provide a JSON model.');
    }

    public static function invalidSize(string $size): DenkException
    {
        return new self("Invalid size: $size.");
    }

    public static function invalidQuality(string $quality): DenkException
    {
        return new self("Invalid quality: $quality.");
    }
}
