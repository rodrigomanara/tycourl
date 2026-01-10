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
        if(is_null($this->getToken()))
        {
            throw new \Exception('Token not found');
        }
        $this->validateToken = new ValidateDecodeToken($this->getToken());
    }
    /**
     * @return string|null
     */
    private function getToken(): ?string
    {
        if(!isset($this->headers['Authorization'])) {
            return null;
        }
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