<?php

use Denk\DenkService;
use Denk\Exceptions\DenkException;
use Denk\Generators\TextGenerator;
use Denk\ValueObjects\UserMessage;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use OpenAI\Responses\StreamResponse;

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

function fakeStreamedText(array $responses = [])
{
    if (empty($responses)) {
        $responses = [
            CreateStreamedResponse::fake(),
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
        ->prompt('What is your name?');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('What is your name?');
});

it('can set a model', function () {
    $denk = fakeText()
        ->model('gpt-4o-mini');

    $invaded = invade($denk);

    expect($invaded->model)->toBe('gpt-4o-mini');
});

it('generates text', function () {
    $textGenerator = fakeText();

    expect($textGenerator->prompt('')->generate())->toBe('Hello!');
});

it('can set a temperature', function () {
    $denk = fakeText()
        ->temperature(0.5);

    $invaded = invade($denk);

    expect($invaded->temperature)->toBe(0.5);
});

it('can generate a streamed response', function () {
    $textGenerator = fakeStreamedText()
        ->prompt('Hello');

    $response = $textGenerator->generateStreamed();

    expect($response)->toBeInstanceOf(StreamResponse::class);
});

it('throws an exception when used without a prompt or messages for streamed response', function () {
    fakeStreamedText()->generateStreamed();
})->throws(DenkException::class);
