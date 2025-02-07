<?php

namespace Denk\Collections;

use Denk\Contracts\Message;
use Illuminate\Support\Collection;

/**
 * @extends Collection<int, Message>
 */
class MessageCollection extends Collection
{
    public function toArray(): array
    {
        return $this->map(fn (Message $message) => $message->toArray())->all();
    }
}
