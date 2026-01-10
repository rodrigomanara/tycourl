<?php

namespace Codediesel\Library;


class DataWithHeaderFormatting
{
    /**
     * @param array $data
     * @return void
     */
    public function success(array $data): void
    {
        header("HTTP/1.1 200 Success"); // Set the 404 status
        $this->dataFormating(['data' => $data, 'success' => true]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function error(array $data): void
    {
        header("HTTP/1.1 500 Error"); // Set the 404 status
        $this->dataFormating(['errorMessage' => $data, 'success' => false]);
    }

    public function notMethod(array $data): void
    {
        header("HTTP/1.1 408 Method not Authorise"); // Set the 404 status
        $this->dataFormating(['errorMessage' => $data, 'success' => false]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function notFound(array $data): void
    {
        header("HTTP/1.1 404 Not Found"); // Set the 404 status
        $this->dataFormating(['errorMessage' => $data, 'success' => false]);
    }

    public function notData(array $data): void
    {
        header("HTTP/1.1 503 Not Found"); // Set the 404 status
        $this->dataFormating(['errorMessage' => $data, 'success' => false]);
    }

    public function notAuthorized(array $data): void
    {
        header("HTTP/1.1 401 Not Authorized"); 
        $this->dataFormating(['errorMessage' => $data, 'success' => false]);
    }

    public function failedToLogin(array $data){
        header("HTTP/1.1 301 Failed"); // Set the 404 status
        $this->dataFormating(['errorMessage' => $data, 'success' => false]);
        
    }

    /**
     * @param array $data
     * @return void
     */
    private function dataFormating(array $data): void
    {
        header("Content-Type: application/json"); // Example of setting a content type
        print(json_encode(['response' => $data]));
        exit;
    }
}