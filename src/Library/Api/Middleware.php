<?php

namespace Codediesel\Library\Api;

use Codediesel\Library\Api\TokenFactory\Authenticate;
use Codediesel\Library\Api\TokenFactory\Authorisation;

/**
 * Middleware class responsible for handling authentication and authorisation
 * for API requests based on the provided type of operation.
 */
class Middleware
{
    // The type of operation being performed
    private string $type;  

    /**
     * Constructor to initialize the Middleware with the operation type.
     *
     * @param string $type The type of operation being performed.
     */
    public function __construct(string $type)
    {
            $this->type = $type;
    }
    /**
     * Initialize the authentication object.
     *
     * @return Authenticate The authentication object.
     */
    private function authenticate() : Authenticate { 
           return  new Authenticate(); 
    }
    /**
     * Initialize the authentication and authorisation objects.
     *
     * @return Authorisation The authorisation object.
     */
    private function authorisation() : Authorisation{
        // Initialize authentication and authorisation objects 
        return new Authorisation();
 }

    /**
     * Checks if the user is authorised to perform the operation.
     *
     * @return bool True if the user is authorised, false otherwise.
     */
    public function isAuthorise(): bool
    {
   // Retrieve payload data from the authentication handler
        $data = $this->authenticate()->handler()->getPayload();
        // Check if the user has the correct permission to perform the operation
        return $this->authorisation()->isAllowed($this->type);
    }

    /**
     * Placeholder method to check if the user is allowed to perform an action.
     *
     * @return bool Always returns true (to be implemented with actual logic).
     */
    public function isUserAllowed(RestApiInterface $class , array $role): bool
    {
        
        $data = $this->authenticate()->handler()->getPayload();
        return true;
        //return $class->isAuthorise('admin');
    }
}