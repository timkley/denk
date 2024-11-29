<?php

namespace Denk\Collections;

use Denk\Contracts\Message;
use Denk\ValueObjects\SystemMessage;
use Illuminate\Support\Collection;

/**
 * @extends Collection<int, Message>
 */
class MessageCollection extends Collection
{
    /**
     * @return Collection<int, Message>
     */
    public function sorted(): Collection
    {
        return $this->partition(fn ($message) => $message instanceof SystemMessage)
            ->flatMap(fn ($partition) => $partition);
    }

    public function toArray(): array
    {
        return $this->sorted()->map(fn (Message $message) => $message->toArray())->all();
    }
}
