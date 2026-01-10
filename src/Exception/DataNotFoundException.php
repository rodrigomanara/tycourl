<?php

namespace Codediesel\Exception;

use Exception;

class DataNotFoundException extends Exception
{
    public function __construct($message = "Data not found", $code = 503, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}