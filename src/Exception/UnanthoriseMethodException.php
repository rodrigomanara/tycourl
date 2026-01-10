<?php


namespace Codediesel\Exception;

use Exception;

class UnanthoriseMethodException extends Exception
{
    public function __construct($message = "Wrong Method, please check documentation", $code = 304, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}