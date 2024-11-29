<?php

namespace Denk\Concerns;

use Denk\Collections\MessageCollection;
use Denk\Contracts\Message;
use Denk\Exceptions\DenkException;
use Denk\ValueObjects\SystemMessage;
use Denk\ValueObjects\UserMessage;

trait TextPrompts
{
    /** @var MessageCollection<int, Message>|null */
    protected ?MessageCollection $messages = null;

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

    public function systemPrompt(string $content): self
    {
        if ($this->getMessages()->filter(fn ($message) => $message instanceof SystemMessage)->isNotEmpty()) {
            throw DenkException::onlyOneSystemMessage();
        }

        $this->getMessages()->push(new SystemMessage($content));

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
}
