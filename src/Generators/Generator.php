<?php

declare(strict_types=1);

namespace Denk\Generators;

use OpenAI\Client;
use OpenAI\Testing\ClientFake;

abstract class Generator
{
    protected Client|ClientFake $client;

    public function __construct(Client|ClientFake $client)
    {
        $this->client = $client;
    }
}
