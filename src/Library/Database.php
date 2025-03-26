<?php

namespace Codediesel\Library;

use Codediesel\Library\Database\InterfaceDatabase;
use Codediesel\Library\Database\Type\Mysql;

class Database
{

    public function init(string $type) : InterfaceDatabase
    {
        return match($type){
            'mysql' => new Mysql()
        };
    }
}