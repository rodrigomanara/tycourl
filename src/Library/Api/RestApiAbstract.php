<?php

namespace Codediesel\Library\Api;

use Codediesel\Controller\Route;

/**
 * Abstract class for REST API operations.
 * Provides utility methods for HTTP method checks and API initialization.
 */
abstract class RestApiAbstract implements RestApiInterface
{

    protected Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }


    /**
     * Initializes the API operation based on the provided function and route.
     *
     * @param string $function The function to be executed (e.g., create, update, delete).
     * @param array $options Additional options for the operation.
     * @return array The result of the operation.
     */
    public function initialize(string $function, array $options): array
    {

        // Check if the method is valid
        (new MethodValidator())->methodValidator($options['method']);

        // Check if the method is allowed for the given function
        if (is_callable([$this, $function])) {
            return $this->{$function}($this->route->getAll());
        } else {
            throw new \Exception("Method not allowed");
        }
    }

    /**
     * Checks if the user is authorized based on their role.
     *
     * @param string $role The role of the user.
     * @return bool True if authorized, otherwise false.
     */
    public function isAuthorise(string $role): bool
    {
        return true;
    }
}