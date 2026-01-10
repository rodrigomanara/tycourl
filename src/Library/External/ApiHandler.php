<?php

namespace Codediesel\Library\External;

use GuzzleHttp\Client;

class ApiHandler
{
    public function client(array $options = []) : Client
    {
        return  new Client($options);  
    }
}