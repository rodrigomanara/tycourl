<?php


namespace Codiesel\Library\Extenal\CloudFlare;


class CloudFlare
{
    private $apiKey;
    private $email;
    private $zoneId;

    public function __construct($apiKey, $email, $zoneId)
    {
        $this->apiKey = $apiKey;
        $this->email = $email;
        $this->zoneId = $zoneId;
    }

    
}