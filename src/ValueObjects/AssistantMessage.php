<?php

declare(strict_types=1);

namespace Denk\ValueObjects;

use Denk\Contracts\Message;

class AssistantMessage implements Message
{
    public function __construct(public readonly string $content) {}

    public function toArray(): array
    {
        return [
            'role' => 'assistant',
            'content' => $this->content,
        ];
    }
}
