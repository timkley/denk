<?php

namespace Denk\Concerns;

use Denk\Collections\MessageCollection;
use Denk\Contracts\Message;
use Denk\ValueObjects\UserMessage;

trait TextPrompts
{
    /** @var MessageCollection<int, Message>|null */
    protected ?MessageCollection $messages = null;

    protected float $temperature = 1.0;

    protected function getMessages(): MessageCollection
    {
        if ($this->messages === null) {
            $this->messages = new MessageCollection;
        }

        return $this->messages;
    }

    public function prompt(string $content): self
    {
        $this->getMessages()->push(new UserMessage($content));

        return $this;
    }

    /**
     * @param  array<Message>  $messages
     */
    public function messages(array $messages): self
    {
        $this->messages = new MessageCollection($messages);

        return $this;
    }

    public function temperature(float $temperature = 1.0): self
    {
        $this->temperature = $temperature;

        return $this;
    }
}
