<?php

use Denk\DenkService;
use Denk\Exceptions\DenkException;
use Denk\Generators\ImageGenerator;
use OpenAI\Responses\Images\CreateResponse;

function fakeImage(array $responses = [])
{
    if (empty($responses)) {
        $responses = [
            CreateResponse::fake(
                [
                    'data' => [
                        [
                            'url' => 'https://example.com/image.jpg',
                        ],
                    ],
                ],
            ),
        ];
    }

    $denk = new DenkService(new OpenAI\Testing\ClientFake($responses));

    return $denk->image();
}

it('returns the image generator', function () {
    expect(fakeImage())->toBeInstanceOf(ImageGenerator::class);
});

it('accepts a prompt', function () {
    $denk = fakeImage()
        ->prompt('This is my prompt');

    $invaded = invade($denk);

    expect($invaded->prompt)->toBe('This is my prompt');
});

it('can set the size', function (string $expected) {
    $denk = fakeImage()
        ->size($expected);

    $invaded = invade($denk);

    expect($invaded->size)->toBe($expected);
})->with([
    ['1024x1024'],
    ['1792x1024'],
    ['1024x1792'],
]);

it('can set the quality', function (string $quality) {
    $denk = fakeImage()
        ->quality($quality);

    $invaded = invade($denk);

    expect($invaded->quality)->toBe($quality);
})->with([
    ['standard'],
    ['hd'],
]);

it('throws an exception when used without a prompt', function () {
    fakeImage()->generate();
})->throws(DenkException::class);

it('generates an image', function () {
    $imageGenerator = fakeImage();

    expect($imageGenerator->prompt('')->generate())->toBe('https://example.com/image.jpg');
});
