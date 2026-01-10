<?php

namespace Codediesel\RestApi\Users;

class RequestPasswordChange
{
    private string $email;
    
 

    public function request(): bool
    {
        //check if email exists
        // if not, return false
        return true; // Simulate successful request
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

}