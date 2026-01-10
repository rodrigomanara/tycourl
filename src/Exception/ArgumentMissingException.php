<?php

namespace Codediesel\Exception;

use Exception;

class ArgumentMissingException extends Exception
{
    public function __construct(string $message = "Missing Argument", int $code = 504, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}