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
    private array $options;  
    /**
     * Constructor
     *
     * @param array $options The options for the middleware.
     */
    public function __construct(array $options)
    {
            $this->options = $options;
            $this->getUserId();
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
     * Placeholder method to check if the user is authenticated.
     *
     * @return bool Always returns true (to be implemented with actual logic).
     */
    public function isAuthenticate(): bool
    {
        // Check if the user is authenticated
        return $this->authenticate()->isValidToken();
    }

    /**
     * Placeholder method to check if the user is allowed to perform an action.
     *
     * @return bool Always returns true (to be implemented with actual logic).
     */
    public function isUserAllowed(RestApiInterface $class): bool
    {
        return $this->authorisation()->isAllowed($this->options['action']);
    }

    /**
     * @return mixed|null
     */
    public function getUserId():?string
    {
        $parsed = $this->authorisation()->parsedToken();
        if(isset($parsed['user_id'])){
            $_SESSION['user_id'] = $parsed['user_id'];
            return $parsed['user_id'];
        }
        return null;
    }
}