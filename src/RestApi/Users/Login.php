<?php

namespace Codediesel\RestApi\Users;

use Codediesel\Controller\Route;
use Codediesel\Exception\AuthenticationException;
use Codediesel\Library\Api\TokenFactory\TokenIssuer;
use Codediesel\Model\Factory\Users;
use Codediesel\Type\RoleType;


class Login
{
    private string $username;
    private string $password;
    private int $user_id;
    private Permission $permission;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->permission = new Permission();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function authentication(): string
    {

        $user = new Users();
        $data = $user->userLogin([
            'username' => $this->username
        ]);

        if (!isset($data['password']))
            throw new AuthenticationException("Failed to Login");

        //validate password
        if (password_verify($this->password, $data['password'])) {

            $user = new User();
            //check if user is anonymous change it to
            if ($data['role'] === RoleType::ANONYMOUS)
                //update user from
                $user->updateUserRole($data['id'], RoleType::USER);


            //update user access ip
            $user->updateUserIpAfterLogin([
                'user_id' => $data['id'],
                'ip' => $_SERVER['REMOTE_ADDR'],
            ]);

            $this->setUserId($data['id']);
            $authentication = new TokenIssuer();
            return $authentication
                ->setPayloadOptions([
                    'role' => $data['role'],
                    'permission' => static::userPermission($data),
                    'user_id' => $data['id'],
                    'user_ip' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                ])
                ->setTokenExpireTime()
                ->generaToken();
        }

        throw new AuthenticationException("Failed to Login");
    }

    /**
     * Determinate what user can do
     * @param array $data
     * @return array
     */
    private function userPermission(array $data): array
    {

        return match ($data['role']) {
            RoleType::ADMIN => $this->permission->adminCan(),
            RoleType::USER => $this->permission->userLoggedCan(),
            default => $this->permission->userNotLoggedCan(),
        };
    }

    /**
     * @param int $user_id
     */
    private function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @param Route $route
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

}