<?php

declare(strict_types=1);

namespace Denk;

use Denk\Generators\ImageGenerator;
use Denk\Generators\JsonGenerator;
use Denk\Generators\TextGenerator;
use OpenAI\Client;
use OpenAI\Testing\ClientFake;

class DenkService
{
    protected Client|ClientFake $client;

    public function __construct(Client|ClientFake $client)
    {
        $this->client = $client;
    }

    public function text(): TextGenerator
    {
        return new TextGenerator($this->client);
    }

    public function json(): JsonGenerator
    {
        return new JsonGenerator($this->client);
    }

    public function image(): ImageGenerator
    {
        return new ImageGenerator($this->client);
    }
}
