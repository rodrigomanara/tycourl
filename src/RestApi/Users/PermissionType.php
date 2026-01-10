<?php

namespace Codediesel\RestApi\Users;

use Codediesel\Type\RoleType;

enum PermissionType
{

    /**
     * @param $type
     * @return array
     */
    public function permission($type): array
    {
        return match ($type)
        {
            RoleType::USER => $this->user(),
            RoleType::ADMIN => $this->admin(),
            default => $this->anonymous(),
        };
    }

    /**
     * @return array
     */
    private function user(){
        return (new Permission())->userLoggedCan() ;
    }

    /**
     * @return array
     */
    private function admin(){
        return (new Permission())->adminCan();
    }
    /**
     * 
     */
    private function anonymous(){
            return (new Permission())->userNotLoggedCan();
    }
}
