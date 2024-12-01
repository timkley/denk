<?php

use Denk\DenkService;
use Denk\Facades\Denk;
use Denk\Generators\ImageGenerator;
use Denk\Generators\JsonGenerator;
use Denk\Generators\TextGenerator;

it('tests the Denk facade', function () {
    config()->set('denk.openai_api_key', 'test-api-key');
    $service = app(DenkService::class);

    expect($service)->toBeInstanceOf(DenkService::class);
    expect($service->text())->toBeInstanceOf(TextGenerator::class);
    expect($service->json())->toBeInstanceOf(JsonGenerator::class);
    expect($service->image())->toBeInstanceOf(ImageGenerator::class);

    expect(Denk::text())->toBeInstanceOf(TextGenerator::class);
    expect(Denk::json())->toBeInstanceOf(JsonGenerator::class);
    expect(Denk::image())->toBeInstanceOf(ImageGenerator::class);
});
