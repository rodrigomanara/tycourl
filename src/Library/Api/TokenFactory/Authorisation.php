<?php

namespace Codediesel\Library\Api\TokenFactory;

use Codediesel\Model\Factory\Users;
use Codediesel\RestApi\Users\Permission;

class Authorisation extends Authenticate
{
    

    /**
     * @param string $type
     * @return bool
     */
    private function isAdmin(string $type)
    {
        $data = $this->parsedToken();
        return $data['role'] == $type && $data['role'] == 'admin';

    }
    /** 
     * @param string $action
     * @return bool
     */
    public function isAllowed(string $action) : bool
    {

        $data = $this->parsedToken();
        if($this->isAdmin($data['role']))
            return true;

        $userData  = new Users();
        //check if user exists
        $user = $userData->fetchUser([
            'id' => $data['user_id']
        ]);

        if(!$user)
            return false;      

        return Permission::isAllowed($user['role'], $action);

    }

}