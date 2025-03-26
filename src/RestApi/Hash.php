<?php

namespace Codediesel\RestApi;

use Codediesel\Controller\Route;
use Codediesel\Model\Factory\URL;
use Exception;

class Hash extends RestApiAbstract implements RestApiInterface
{

    public function initialize(string $type, Route $route): array
    {
        //middleware
        $authenticate = new Middleware($type);    

            

        //check if user can make api calls


        return parent::initialize($type, $route); // TODO: Change the autogenerated stub
    }

    /**
     * @throws \Exception
     */
    public function create(array $data): array
    {
        if(!$this->isPost())
            throw new \Exception("Wrong Method, please check documentation");

        if(!isset($data['url']))
            throw new \Exception("Missing Argument URL");

        $fetch = (new URL())->createUrl($data['url']);
        if(isset($fetch['error']))
            throw new \Exception($fetch['error']);

        return $fetch;
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function retrieve(array $data): array
    {

        $arguments['hash'] = $data['hash'] ?? null;
        $arguments['url'] = $data['url'] ?? null;
        if(!$this->isPost())
            throw new \Exception("Wrong Method, please check documentation");

        if(!isset($arguments['hash']))
            throw new \Exception("Missing Argument URL");

        $fetch = (new URL())->fetchUrl($arguments['hash']);

        if(isset($fetch['error']))
            throw new \Exception($fetch['error']);

        if(isset($fetch[0]) && $fetch[0] === false)
            throw new \Exception("Data not found");

        return $fetch;
    }

    /**
     * @param string $data
     * @return array
     */
    public function delete(array $data): array
    {
        // TODO: Implement delete() method.
        return [];
    }

    /**
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        // TODO: Implement update() method.
        return [];
    }
}