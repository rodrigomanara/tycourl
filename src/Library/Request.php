<?php

namespace Codediesel\Library;

class Request
{
    private array $request;
    private array $post;
    private array $get;
    private string $input;

    /**
     * Constructor
     *
     * Initializes the Request class by setting up the request, GET, POST, and input data.
     */
    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->input = file_get_contents("php://input");
    }

    /**
     * Get all request parameters
     *
     * @return array|null An array of all request parameters or null if not found
     */
    public function request(): ?array
    {
        return $this->request;
    }

    /**
     * Get GET request parameters
     *
     * @return array|null An array of GET request parameters or null if not found
     */
    public function get(): ?array
    {
        return $this->get;
    }

    /**
     * Get POST request parameters
     *
     * @return array|null An array of POST request parameters or null if not found
     */
    public function post(): ?array
    {
        return $this->post;
    }

    /**
     * Get raw input data
     *
     * @return false|string The raw input data as a string or false if not found
     */
    public function input(): false|string
    {
        return $this->input;
    }

}