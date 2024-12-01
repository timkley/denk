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

it('can set the size', function (string $input, string $output) {
    $denk = fakeImage()
        ->size($input);

    $invaded = invade($denk);

    expect($invaded->size)->toBe($output);
})->with([
    ['square', '1024x1024'],
    ['landscape', '1792x1024'],
    ['portrait', '1024x1792'],
]);

it('does not set an invalid size', function () {
    $denk = fakeImage()
        ->size('invalid');
})->throws(DenkException::class);

it('can set the quality', function (string $quality) {
    $denk = fakeImage()
        ->quality($quality);

    $invaded = invade($denk);

    expect($invaded->quality)->toBe($quality);
})->with([
    ['standard'],
    ['hd'],
]);

it('does not set an invalid quality', function () {
    $denk = fakeImage()
        ->quality('invalid');
})->throws(DenkException::class);

it('throws an exception when used without a prompt', function () {
    fakeImage()->generate();
})->throws(DenkException::class);

it('generates an image', function () {
    $imageGenerator = fakeImage();

    expect($imageGenerator->prompt('')->generate())->toBe('https://example.com/image.jpg');
});
