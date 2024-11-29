<?php

use Denk\Denk;
use Denk\Exceptions\DenkException;
use Denk\Generators\TextGenerator;
use Denk\ValueObjects\SystemMessage;
use Denk\ValueObjects\UserMessage;

it('returns the text generator', function () {
    expect(Denk::text())->toBeInstanceOf(TextGenerator::class);
});

it('accepts a prompt', function () {
    $denk = Denk::text()
        ->prompt('This is my prompt');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('This is my prompt');
});

it('accepts messages', function () {
    $denk = Denk::text()
        ->messages([
            new UserMessage('This is my prompt'),
        ]);

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('This is my prompt');
});

it('throws an exception when used without a prompt or messages', function () {
    TextGenerator::fake()->generate();
})->throws(DenkException::class);

it('accepts a system prompt', function () {
    $denk = Denk::text()
        ->prompt('What is your name?')
        ->systemPrompt('Answer as a pirate.');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('What is your name?');
    expect($invaded->messages[1])->toBeInstanceOf(SystemMessage::class)->and($invaded->messages[1]->content)->toBe('Answer as a pirate.');
});

it('only accepts one system prompt', function () {
    Denk::text()
        ->systemPrompt('Answer as a pirate.')
        ->systemPrompt('Answer as a pirate.');
})->throws(DenkException::class);

it('can set a model', function () {
    $denk = Denk::text()
        ->model('gpt-4o-mini');

    $invaded = invade($denk);

    expect($invaded->model)->toBe('gpt-4o-mini');
});

it('can not set an invalid model', function () {
    Denk::text()->model('invalid-model');
})->throws(DenkException::class);

it('generates text', function () {
    $textGenerator = TextGenerator::fake();

    expect($textGenerator->prompt('')->generate())->toBe('Hello!');
});
