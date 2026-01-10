<?php

namespace Codediesel\RestApi\Users;

class Permission
{

    CONST string CREATE = 'create';
    CONST string DELETE = 'delete';
    CONST string UPDATE = 'update';
    CONST string RETRIEVE = 'retrieve';
    CONST string ALL = 'all';
    
    /**
     * @return array
     */
    public function adminCan() : array{
        return [static::CREATE , static::DELETE , static::RETRIEVE , static::UPDATE , static::ALL];
    }
    /**
     * @return array
     */
    public function userNotLoggedCan() : array{
        return [static::RETRIEVE];
    }
    /**
     * @return array
     */
    public function userLoggedCan() : array
    {
        return [static::CREATE , static::DELETE , static::RETRIEVE , static::UPDATE ];
    }
    /**
     * @param string $role
     * @param string $action
     * @return bool
     */

    public static function isAllowed(string $role, string $action) : bool
    {
        $permission = new static();
        $allowed = $permission->userNotLoggedCan();

        if($role == 'admin')
            $allowed = $permission->adminCan();
        elseif($role == 'user')
            $allowed = $permission->userLoggedCan();

        return in_array($action, $allowed);
    }
}