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
    private function actionChecker(string $type)
    {
        $data = $this->parsedToken();
        return $data['action'] == $type;

    }

    /**
     * @param string $type
     * @return bool
     */
    private function isAdmin(string $type)
    {
        $data = $this->parsedToken();
        return $data['role'] == $type && $data['role'] == 'admin';

    }


    public function isAllowed(string $role) : bool
    {
        $data = $this->parsedToken();
        if($this->isAdmin($role))
            return true;

       $userData  = new Users();
        $user = $userData->fetchUser([
            'id' => $data['user_id']
        ]);

        if(!$user)
            return false;

        
        //check if user has the correct permission to perform the operation
        return Permission::check($user['role'], $role);        

    }

}