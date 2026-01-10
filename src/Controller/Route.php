<?php

namespace Codediesel\Controller;

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

    public function getUserSessionID()
    {
        return $this->request->session()['user_id'] ?? null;
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
        $request = [];
        if ($this->request->get()) {
            $request[] = $this->request->get();
        }

        if ($this->request->request()) {
            $request[] = $this->request->request();
        }
        if ($this->request->post()) {
            $request[] = $this->request->post();
        }

        if ($this->request->input()) {
            $request[] = json_decode($this->request->input(), true);
        }

        if($this->request->session())
            $request[] = $this->request->session();


        $combine = [];
        foreach ($request as $key => $value) {
            if (is_array($value)) {
                $combine = array_merge($combine, $value);
            }
        }

        return $combine;

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

    public function set(string $key, mixed $value)
    {
        $_REQUEST[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isMatch(string $key): bool
    {

        //extract everything after  ?
        $requestUri = explode('?', $_SERVER['REQUEST_URI']);
        return current($requestUri) == $key ?? false;
    }
}