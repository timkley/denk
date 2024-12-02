<?php

declare(strict_types=1);

namespace Denk;

use OpenAI\Testing\ClientFake;

class DenkFake extends DenkService
{
    public function __construct(array $responses = [])
    {
        $clientFake = new ClientFake($responses);
        parent::__construct($clientFake);
    }
}
