<?php

declare(strict_types=1);

namespace Denk\Generators;

use OpenAI;
use OpenAI\Client;
use OpenAI\Testing\ClientFake;

abstract class Generator
{
    protected Client|ClientFake $client;

    public function __construct(Client|ClientFake|null $client = null)
    {
        $openaiApiKey = env('OPENAI_API_KEY', '');

        if (! is_string($openaiApiKey)) {
            throw new \Exception('OPENAI_API_KEY is not set');
        }

        $this->client = $client ?? OpenAI::client($openaiApiKey);
    }
}
