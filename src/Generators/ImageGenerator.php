<?php

declare(strict_types=1);

namespace Denk\Generators;

use Denk\Exceptions\DenkException;
use OpenAI;
use OpenAI\Responses\Images\CreateResponse;

class ImageGenerator extends Generator
{
    protected ?string $prompt = null;

    protected ?string $size = 'square';

    protected ?string $quality = 'standard';

    public function prompt(string $prompt): self
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function size(string $size): self
    {
        if (! in_array($size, ['square', 'landscape', 'portrait'])) {
            throw DenkException::invalidSize($size);
        }

        match ($size) {
            'square' => $size = '1024x1024',
            'portrait' => $size = '1024x1792',
            'landscape' => $size = '1792x1024',
        };

        $this->size = $size;

        return $this;
    }

    public function quality(string $quality): self
    {
        if (! in_array($quality, ['standard', 'hd'])) {
            throw DenkException::invalidQuality($quality);
        }

        $this->quality = $quality;

        return $this;
    }

    public function generate(): string
    {
        if (is_null($this->prompt)) {
            throw DenkException::noPrompt();
        }

        $data = $this->client->images()->create([
            'model' => 'dall-e-3',
            'prompt' => $this->prompt,
            'size' => '256x256',
            'quality' => 100,
        ]);

        if (data_get($data, 'error')) {
            /** @var string $error_type */
            $error_type = data_get($data, 'error.type', 'unknown');
            /** @var string $error_message */
            $error_message = data_get($data, 'error.message', 'unknown');

            throw DenkException::openAiError(
                vsprintf(
                    'OpenAI Error:  [%s] %s',
                    [
                        $error_type,
                        $error_message,
                    ]
                )
            );
        }

        /** @var string $url */
        $url = data_get($data, 'data.0.url');

        return $url;
    }

    /**
     * @param  array<CreateResponse>  $responses
     */
    public static function fake(array $responses = []): self
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

        return new self(new OpenAI\Testing\ClientFake($responses));
    }
}
