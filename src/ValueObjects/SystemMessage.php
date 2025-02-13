<?php

declare(strict_types=1);

namespace Denk\ValueObjects;

use Denk\Contracts\Message;

/**
 * @deprecated Use DeveloperMessage instead
 */
class SystemMessage implements Message
{
    public function __construct(public readonly string $content) {}

    public function toArray(): array
    {
        return [
            'role' => 'system',
            'content' => $this->content,
        ];
    }
}
