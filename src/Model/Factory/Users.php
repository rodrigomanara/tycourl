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
    public function fetchUser(array $arguments): mixed
    {
        return $this->getInstance()->retrieve(static::table_name, $arguments);
    }

    /**
     * @param array $arguments
     * @return array
     */
    public function createUser(array $arguments): array
    {
        return $this->getInstance()->create(static::table_name, $arguments);
    }

    /**
     * @param array $arguments
     * @return array
     */
    public function userLogin(array $arguments): array
    {
        return $this->getInstance()->retrieve(static::table_name, $arguments);
    }

    /**
     * @param array $arguments
     * @param array $where
     * @return bool
     */
    public function changePassword(array $arguments, array $where = [])
    {

        /**
         * Hash the password before storing it in the database
         */
        if (isset($arguments['password'])) {
            $arguments['password'] = password_hash($arguments['password'], PASSWORD_BCRYPT);
        }

        $data = $this->getInstance()->update(static::table_name, $arguments, $where);
        if ($data) {
            return true;
        }
        return false;
    }

    /**
     * @param array $arguments
     * @param array $where
     * @return bool
     */
    public function update(array $arguments, array $where)
    {
        $data = $this->getInstance()->update(static::table_name, $arguments, $where);
        if ($data) {
            return true;
        }
        return false;
    }
}