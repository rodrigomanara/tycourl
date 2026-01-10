<?php

namespace Codediesel\RestApi\Users;

use Codediesel\Model\Factory\Users;

class User
{


    public function findUserByEmail(string $email): array
    {
        $user = new Users();
        return $user->fetchUser([
            'username' => $email
        ]);
    }

    public function findUserById(string $id): array
    {
        $user = new Users();
        return $user->fetchUser([
            'id' => $id
        ]);
    }

    /**
     * @param string $username
     * @return array
     */
    public function findUserByUsername(string $username): array
    {
        $user = new Users();
        return $user->fetchUser([
            'username' => $username
        ]);
    }

    /**
     * update user password.
     * @throws \Exception
     */
    public function updateUserPassword(string $id, string $password): bool
    {
        $user = new Users();
        return $user->changePassword([
            'password' => $password
        ], [
            'id' => $id
        ]);
    }

    /**
     * @param string $id
     * @param string $role
     * @return bool
     */
    public function updateUserRole(string $id, string $role): bool
    {
        $user = new Users();
        return $user->update([
            'role' => $role
        ], [
            'id' => $id
        ]);
    }

    /**
     * @param array $array
     * @return bool
     */
    public function updateUserIpAfterLogin(array $array)
    {
        $user = new Users();
        return $user->update([
            'ip' => $array['ip']
        ], [
            'id' => $array['user_id']
        ]);
    }

}