<?php

use Denk\Denk;
use Denk\Exceptions\DenkException;
use Denk\Generators\ImageGenerator;

it('returns the image generator', function () {
    expect(Denk::image())->toBeInstanceOf(ImageGenerator::class);
});

it('accepts a prompt', function () {
    $denk = Denk::image()
        ->prompt('This is my prompt');

    $invaded = invade($denk);

    expect($invaded->prompt)->toBe('This is my prompt');
});

it('can set the size', function (string $input, string $output) {
    $denk = Denk::image()
        ->size($input);

    $invaded = invade($denk);

    expect($invaded->size)->toBe($output);
})->with([
    ['square', '1024x1024'],
    ['landscape', '1792x1024'],
    ['portrait', '1024x1792'],
]);

it('does not set an invalid size', function () {
    $denk = Denk::image()
        ->size('invalid');
})->throws(DenkException::class);

it('can set the quality', function (string $quality) {
    $denk = Denk::image()
        ->quality($quality);

    $invaded = invade($denk);

    expect($invaded->quality)->toBe($quality);
})->with([
    ['standard'],
    ['hd'],
]);

it('does not set an invalid quality', function () {
    $denk = Denk::image()
        ->quality('invalid');
})->throws(DenkException::class);

it('throws an exception when used without a prompt', function () {
    ImageGenerator::fake()->generate();
})->throws(DenkException::class);

it('generates an image', function () {
    $imageGenerator = ImageGenerator::fake();

    expect($imageGenerator->prompt('')->generate())->toBe('https://example.com/image.jpg');
});
