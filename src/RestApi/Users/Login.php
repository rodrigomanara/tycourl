<?php

namespace Codediesel\RestApi\Users;

use Codediesel\Controller\Route;
use Codediesel\Exception\AuthenticationException;
use Codediesel\Model\Factory\Users;
use Codediesel\Library\Api\TokenFactory\TokenIssuer;
use Codediesel\RestApi\Users\RoleType;

class Login
{
    private string $username;
    private string $password;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
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
         
        if(!isset($data['password']))
            throw new AuthenticationException("Failed to Login");

        //validate password
        if (password_verify($this->password, $data['password']))
        {
            $authentication  = new TokenIssuer();
            return $authentication
                ->setPayloadOptions([
                    'role' => $data['role'],
                    'permission' => static::userPermission($data),
                    'user_id' => $data['id']
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
    private static function userPermission(array $data): array
    {
        return match($data['role'])
        {
            RoleType::ADMIN => (new Permission())->adminCan(),
            RoleType::USER => (new Permission())->userLoggedCan(),
            default => (new Permission())->userNotLoggedCan(),
        };
    }

}