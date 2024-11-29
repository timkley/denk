<?php

declare(strict_types=1);

namespace Denk\Contracts;

interface Message
{
    /**
     * @return array<string, string>
     */
    public function toArray();
}
