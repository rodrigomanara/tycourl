<?php

namespace Codediesel\RestApi;
use Codediesel\RestApi\Users\Login;
use Codediesel\Library\Api\RestApiAbstract;
use Codediesel\Library\Api\RestApiInterface;

/**
 * Class Authenticate
 * @package Codediesel\RestApi
 */
class Authenticate  extends RestApiAbstract implements RestApiInterface
{

    /**
     * @throws \Exception
     */
    public function create(array $data): array
    {
        if(!$this->isPost())
            throw new \Exception("Wrong Method, please check documentation");


        if(isset($data['username'] , $data['password'])){
            $login = new Login($data['username'] , $data['password']);
            return [
                'token' => $login->authentication() 
            ];
        }

        throw new \Exception("Something went wrong");
        
    }

    /**
     *  will deal with refresh token
     * @param array $data
     * @return array
     */
    public function retrieve(array $data): array
    {

        return [];
    }

    public function delete(array $data): array
    {
        return [];
    }
    /**
     * will deal with refresh token
     */
    public function update(array $data): array
    {
        return [];
    }
}