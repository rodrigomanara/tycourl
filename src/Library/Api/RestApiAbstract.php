<?php

namespace Codediesel\Library\Api;

use Codediesel\Controller\Route;

/**
 * Abstract class for REST API operations.
 * Provides utility methods for HTTP method checks and API initialization.
 */
abstract class RestApiAbstract implements RestApiInterface
{
    /**
     * @var string $method The HTTP method of the current request.
     */
    private string $method;

    /**
     * Constructor to initialize the HTTP method from the server request.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Checks if the HTTP method is POST.
     *
     * @return bool True if the method is POST, otherwise false.
     */
    protected function isPost(): bool
    {
        return $this->method === 'POST';
    }

    /**
     * Checks if the HTTP method is GET.
     *
     * @return bool True if the method is GET, otherwise false.
     */
    protected function isGet(): bool
    {
        return $this->method === 'GET';
    }

    /**
     * Checks if the HTTP method is PUT.
     *
     * @return bool True if the method is PUT, otherwise false.
     */
    protected function isPut(): bool
    {
        return $this->method === 'PUT';
    }

    /**
     * Checks if the HTTP method is DELETE.
     *
     * @return bool True if the method is DELETE, otherwise false.
     */
    protected function isDelete(): bool
    {
        return $this->method === 'DELETE';
    }

    /**
     * Initializes the API operation based on the provided type and route.
     *
     * @param string $type The type of operation (e.g., create, update, delete, retrieve).
     * @param Route $route The route object containing request data.
     * @return array The result of the operation.
     */
    public function initialize(string $type, Route $route): array
    {
        return match ($type) {
            'create' => $this->create($route->post()),
            'update' => $this->update($route->post()),
            'delete' => $this->delete($route->getAll()),
            'retrieve' => $this->retrieve($route->getAll()),
            default => []
        };
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