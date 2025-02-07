<?php

use Denk\Collections\MessageCollection;
use Denk\ValueObjects\AssistantMessage;
use Denk\ValueObjects\DeveloperMessage;
use Denk\ValueObjects\UserMessage;

it('can format the messages correctly', function () {
    $messages = new MessageCollection([
        new DeveloperMessage('developer message'),
        new UserMessage('user message'),
        new AssistantMessage('assistant message'),
        new UserMessage('user 2 message'),
        new AssistantMessage('assistant 2 message'),
    ]);

    expect($messages->toArray())->toBe([
        [
            'role' => 'developer',
            'content' => 'developer message',
        ],
        [
            'role' => 'user',
            'content' => 'user message',
        ],
        [
            'role' => 'assistant',
            'content' => 'assistant message',
        ],
        [
            'role' => 'user',
            'content' => 'user 2 message',
        ],
        [
            'role' => 'assistant',
            'content' => 'assistant 2 message',
        ],
    ]);
});
