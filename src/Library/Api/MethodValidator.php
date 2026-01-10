<?php

namespace Codediesel\Library\Api;

use Codediesel\Exception\UnanthoriseMethodException;

class MethodValidator
{

    /**
     * Constructor to initialize the HTTP method from the server request.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
    }
    /**
     * @var string The HTTP method (e.g., GET, POST, PUT, DELETE).
     */
    private string $method;
    
    
    /**
     * Static method to validate the HTTP method for the operation.
     *
     * @param string $method The HTTP method to validate.
     * @throws UnanthoriseMethodException if the method is not allowed.
     */
    public  function methodValidator( string $method): true
    {

        if(($this->isPost()
        || $this->isGet()
        || $this->isPut()
        || $this->isDelete())
        && $this->isValid($method , $this->method)) {
            return true;
        }

        throw new UnanthoriseMethodException("Wrong Method, please check documentation");



    }
    /**
     * Checks if the provided method is valid for the current operation.
     *
     * @param string $method The HTTP method to check.
     * @param string $type The expected HTTP method type (e.g., GET, POST).
     * @return bool True if the method is valid, otherwise false.
     */
    private function isValid(string $method , string $type): bool
    {
        return strtoupper($method) === strtoupper($type);
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
}