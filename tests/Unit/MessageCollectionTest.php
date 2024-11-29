<?php

use Denk\Collections\MessageCollection;
use Denk\ValueObjects\AssistantMessage;
use Denk\ValueObjects\SystemMessage;
use Denk\ValueObjects\UserMessage;

it('sorts a system message to the first while leaving all other messages untouched', function () {
    $messages = new MessageCollection([
        new UserMessage('user message'),
        new AssistantMessage('assistant message'),
        new UserMessage('user 2 message'),
        new AssistantMessage('assistant 2 message'),
        new SystemMessage('system message'),
    ]);

    $sorted = $messages->sorted();

    expect($sorted->first())->toBeInstanceOf(SystemMessage::class);
    expect($sorted->first()->content)->toBe('system message');
    expect($sorted->last())->toBeInstanceOf(AssistantMessage::class);
    expect($sorted->last()->content)->toBe('assistant 2 message');
});

it('can format the messages correctly', function () {
    $messages = new MessageCollection([
        new UserMessage('user message'),
        new AssistantMessage('assistant message'),
        new UserMessage('user 2 message'),
        new AssistantMessage('assistant 2 message'),
        new SystemMessage('system message'),
    ]);

    expect($messages->toArray())->toBe([
        [
            'role' => 'system',
            'content' => 'system message',
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
