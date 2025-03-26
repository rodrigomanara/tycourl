<?php

namespace Codediesel\Controller;

use Codediesel\Model\Factory\URL;
use Codediesel\Library\Request;

class Route
{

    private Request $request;

    /**
     * Constructor
     *
     * Initializes the Route class by creating a new Request instance.
     */
    public function __construct()
    {
       $this->request = new Request();
    }

    /**
     * Get a specific request parameter by key
     *
     * @param string $key The key of the request parameter to retrieve
     * @return mixed|null The value of the request parameter or null if not found
     */
    public function get(string $key): mixed
    {
        return $this->request->request()[$key] ?? null;
    }

    /**
     * Get all request parameters
     *
     * This method retrieves all GET or POST request parameters.
     *
     * @return array An array of all request parameters
     */
    public function getAll(): array
    {
        if ($this->request->get()) {
            return $this->request->get();
        }

        if ($this->request->request()) {
            return $this->request->request();
        }

        return [];
    }

    /**
     * Get the request data
     *
     * This method retrieves the request data.
     *
     * @return array|null The request data or null if not found
     */
    public function request(): ?array
    {
        return $this->request->request();
    }

    /**
     * Get the POST request data
     *
     * This method retrieves the POST request data. If no POST data is found,
     * it attempts to retrieve the input data and decode it as JSON.
     *
     * @return array|null The POST request data or null if not found
     */
    public function post(): ?array
    {
        if ($this->request->post()) {
            return $this->request->post();
        }

        if ($this->request->input()) {
            return json_decode($this->request->input(), true);
        }
        

        return null;
    }

    public function set(string $key , mixed $value){
        $_REQUEST[$key] = $value;
    }
}