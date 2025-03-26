<?php


namespace Codediesel\Exception;


class AuthenticationException extends \Exception
{
    public function __construct($message = "Authentication failed", $code = 401, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}