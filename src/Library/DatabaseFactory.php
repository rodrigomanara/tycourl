<?php

namespace Codediesel\Library;

use Codediesel\Library\Database\InterfaceDatabase;

trait DatabaseFactory
{
    /**
     * @return InterfaceDatabase
     */
    private function getInstance() : InterfaceDatabase{
        $connection = new Database();
        return $connection->init($_ENV['DATABASE_TYPE']);
    }
}