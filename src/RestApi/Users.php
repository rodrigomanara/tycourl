<?php

namespace Codediesel\RestApi;

use Codediesel\Model\Factory\Users as UsersFActory;
use Codediesel\RestApi\Users\RoleType;
use Codediesel\Library\Api\RestApiAbstract;
use Codediesel\Library\Api\RestApiInterface;

class Users extends RestApiAbstract implements RestApiInterface
{

    /**
     * @throws \Exception
     */
    public function create(array $data): array
    {
        if(!$this->isPost())
            throw new \Exception("Wrong Method, please check documentation");

        $fetch = (new UsersFActory())->createUser([
            'full_name' => $data['full_name'],
            'username' => $data['email'],
            'password' => password_hash($data['password'], HASH_HMAC),
            'role' => RoleType::USER
        ]);

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
          if(!$this->isGet())
            throw new \Exception("Wrong Method, please check documentation");

        $fetch = (new UsersFActory())->fetchUser([
           'id' => $data['id']
        ]);

        //use set password
        unset($fetch['password']);
        return $fetch;
    }

    public function delete(array $data): array
    {
        return [];
    }

    public function update(array $data): array
    {
        return [];
    }
 

}