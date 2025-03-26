<?php

namespace Codediesel\Library\Api\TokenFactory;

use Codediesel\Library\Api\TokenFactory\ValidateDecodeToken;


class Authenticate
{

    private array|false $headers;
    private ValidateDecodeToken $validateToken ;
    /**
     * Authenticate constructor.
     */
    public function __construct()
    {
        $this->headers = getallheaders();  
        $this->validateToken = new ValidateDecodeToken($this->getToken());
    }
    /**
     * @return string|null
     */
    private function getToken(): ?string
    {
       return str_replace('Bearer ', '', $this->headers['Authorization']);
    }

    /**
     * @return bool
     */
    public function isValidToken(): bool
    {
        return $this->validateToken->validate();
    }

    /**
     * */
    public function parsedToken(): array
    {
        return $this->handler()->getPayload();
    }
    
    /** */
    public function handler(): ValidateDecodeToken
    {
        return $this->validateToken;
    }
}