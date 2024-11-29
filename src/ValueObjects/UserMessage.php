<?php

declare(strict_types=1);

namespace Denk\ValueObjects;

use Denk\Contracts\Message;

class UserMessage implements Message
{
    public function __construct(public readonly string $content) {}

    public function toArray(): array
    {
        return [
            'role' => 'user',
            'content' => $this->content,
        ];
    }
}
