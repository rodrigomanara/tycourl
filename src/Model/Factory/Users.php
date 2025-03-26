<?php

namespace Codediesel\Model\Factory;

use Codediesel\Library\DatabaseFactory;

class Users
{
    use DatabaseFactory;

    const table_name = 'users';

    /**
     * @param array $arguments
     * @return mixed
     */
    public function fetchUser(array $arguments)
    {
        return $this->getInstance()->retrieve(static::table_name , $arguments);
    }


    /**
     * @param array $arguments
     * @return array
     */
    public function createUser(array $arguments): array
    {
        return $this->getInstance()->create(static::table_name , $arguments);
    }

    /**
     * @param array $arguments
     * @return array
     */
    public function userLogin(array $arguments) : array
    {
        return $this->getInstance()->retrieve(static::table_name, $arguments);
    }

    public function changePassword(array $arguments){
        return [];
        //return $this->getInstance()->update(static::table_name , $arguments);
    }
}