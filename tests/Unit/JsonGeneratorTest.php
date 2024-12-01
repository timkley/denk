<?php

use Denk\DenkService;
use Denk\Exceptions\DenkException;
use Denk\Generators\JsonGenerator;
use Denk\ValueObjects\SystemMessage;
use Denk\ValueObjects\UserMessage;
use OpenAI\Responses\Chat\CreateResponse;

function fakeJson(array $responses = [])
{
    if (empty($responses)) {
        $responses = [
            CreateResponse::fake([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'title' => 'Fake title',
                                'description' => 'Fake description',
                            ]),
                        ],
                    ],
                ],
            ]),
        ];
    }

    $denk = new DenkService(new OpenAI\Testing\ClientFake($responses));

    return $denk->json();
}

it('returns the json generator', function () {
    expect(fakeJson())->toBeInstanceOf(JsonGenerator::class);
});

it('accepts a prompt', function () {
    $denk = fakeJson()
        ->prompt('This is my prompt');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('This is my prompt');
});

it('accepts messages', function () {
    $denk = fakeJson()
        ->messages([
            new UserMessage('This is my prompt'),
        ]);

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('This is my prompt');
});

it('throws an exception when used without a prompt or messages', function () {
    fakeJson()->generate();
})->throws(DenkException::class);

it('accepts a system prompt', function () {
    $denk = fakeJson()
        ->prompt('What is your name?')
        ->systemPrompt('Answer as a pirate.');

    $invaded = invade($denk);

    expect($invaded->messages[0])->toBeInstanceOf(UserMessage::class)->and($invaded->messages[0]->content)->toBe('What is your name?');
    expect($invaded->messages[1])->toBeInstanceOf(SystemMessage::class)->and($invaded->messages[1]->content)->toBe('Answer as a pirate.');
});

it('only accepts one system prompt', function () {
    fakeJson()
        ->systemPrompt('Answer as a pirate.')
        ->systemPrompt('Answer as a pirate.');
})->throws(DenkException::class);

it('can set a model', function () {
    $denk = fakeJson()
        ->model('gpt-4o-mini');

    $invaded = invade($denk);

    expect($invaded->model)->toBe('gpt-4o-mini');
});

it('can not set an invalid model', function () {
    fakeJson()->model('gpt-4');
})->throws(DenkException::class);

it('needs a json model', function () {
    fakeJson()
        ->prompt('What is your name?')
        ->generate();
})->throws(DenkException::class);

it('generates json', function () {
    $jsonGenerator = fakeJson();

    expect($jsonGenerator->properties(['title' => 'string'])->prompt('')->generate())->toBe(['title' => 'Fake title', 'description' => 'Fake description']);
});

it('can format the messages correctly', function () {
    $denk = fakeJson()
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
