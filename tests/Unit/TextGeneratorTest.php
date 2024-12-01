<?php

use Denk\DenkService;
use Denk\Exceptions\DenkException;
use Denk\Generators\TextGenerator;
use Denk\ValueObjects\SystemMessage;
use Denk\ValueObjects\UserMessage;
use OpenAI\Responses\Chat\CreateResponse;

function fakeText(array $responses = [])
{
    if (empty($responses)) {
        $responses = [
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Hello!',
                        ],
                    ],
                ],
            ]),
        ];
    }

    $denk = new DenkService(new OpenAI\Testing\ClientFake($responses));

    return $denk->text();
}

it('returns the text generator', function () {
    expect(fakeText())->toBeInstanceOf(TextGenerator::class);
});

it('accepts a prompt', function () {
    $denk = fakeText()
        ->prompt('This is my prompt');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('This is my prompt');
});

it('accepts messages', function () {
    $denk = fakeText()
        ->messages([
            new UserMessage('This is my prompt'),
        ]);

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('This is my prompt');
});

it('throws an exception when used without a prompt or messages', function () {
    fakeText()->generate();
})->throws(DenkException::class);

it('accepts a system prompt', function () {
    $denk = fakeText()
        ->prompt('What is your name?')
        ->systemPrompt('Answer as a pirate.');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('What is your name?');
    expect($invaded->messages[1])->toBeInstanceOf(SystemMessage::class)->and($invaded->messages[1]->content)->toBe('Answer as a pirate.');
});

it('only accepts one system prompt', function () {
    fakeText()
        ->systemPrompt('Answer as a pirate.')
        ->systemPrompt('Answer as a pirate.');
})->throws(DenkException::class);

it('can set a model', function () {
    $denk = fakeText()
        ->model('gpt-4o-mini');

    $invaded = invade($denk);

    expect($invaded->model)->toBe('gpt-4o-mini');
});

it('can not set an invalid model', function () {
    fakeText()->model('invalid-model');
})->throws(DenkException::class);

it('generates text', function () {
    $textGenerator = fakeText();

    expect($textGenerator->prompt('')->generate())->toBe('Hello!');
});
