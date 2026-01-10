<?php

namespace Codediesel\Exception;

use Exception;

class DatabaseErrorException extends Exception
{
    /**
     * DatabaseErrorException constructor.
     *
     * @param string $message The error message from the database.
     * @param int $code The error code (default is 506).
     * @param Exception|null $previous The previous exception (if any).
     */
    public function __construct($message = "Database Error", $code = 506, Exception $previous = null)
    {
        parent::__construct($this->errorCodeHandler($message), $code, $previous);
    }

    /**
     * Handles specific error codes and returns a user-friendly message.
     *
     * @param string $message The error message from the database.
     * @return string A user-friendly error message.
     */
    private function errorCodeHandler(string $message): string
    {
        if(preg_match("/23000/" , $message))
            return "Record already exists";

        return "Database Error";
    }

}