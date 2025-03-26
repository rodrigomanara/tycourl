<?php

namespace Codediesel\Library\Api\TokenFactory;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class Clock implements ClockInterface
{

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}