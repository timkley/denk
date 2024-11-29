<?php

use Denk\Denk;
use Denk\Exceptions\DenkException;
use Denk\Generators\JsonGenerator;
use Denk\Generators\TextGenerator;
use Denk\ValueObjects\SystemMessage;
use Denk\ValueObjects\UserMessage;

it('returns the json generator', function () {
    expect(Denk::json())->toBeInstanceOf(JsonGenerator::class);
});

it('accepts a prompt', function () {
    $denk = Denk::json()
        ->prompt('This is my prompt');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('This is my prompt');
});

it('accepts messages', function () {
    $denk = Denk::json()
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
    $denk = Denk::json()
        ->prompt('What is your name?')
        ->systemPrompt('Answer as a pirate.');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('What is your name?');
    expect($invaded->messages[1])->toBeInstanceOf(SystemMessage::class)->and($invaded->messages[1]->content)->toBe('Answer as a pirate.');
});

it('only accepts one system prompt', function () {
    Denk::json()
        ->systemPrompt('Answer as a pirate.')
        ->systemPrompt('Answer as a pirate.');
})->throws(DenkException::class);

it('can set a model', function () {
    $denk = Denk::json()
        ->model('gpt-4o-mini');

    $invaded = invade($denk);

    expect($invaded->model)->toBe('gpt-4o-mini');
});

it('can not set an invalid model', function () {
    Denk::json()->model('gpt-4');
})->throws(DenkException::class);

it('needs a json model', function () {
    Denk::json()
        ->prompt('What is your name?')
        ->generate();
})->throws(DenkException::class);

it('generates json', function () {
    $jsonGenerator = JsonGenerator::fake();

    expect($jsonGenerator->properties(['title' => 'string'])->prompt('')->generate())->toBe(['title' => 'Fake title', 'description' => 'Fake description']);
});

it('can format the messages correctly', function () {
    $denk = Denk::json()
        ->prompt('What is your name?')
        ->systemPrompt('Answer as a pirate.');

    $invaded = invade($denk);

    expect($invaded->messages->toArray())->toBe([
        [
            'role' => 'system',
            'content' => 'Answer as a pirate.',
        ],
        [
            'role' => 'user',
            'content' => 'What is your name?',
        ],
    ]);
});
