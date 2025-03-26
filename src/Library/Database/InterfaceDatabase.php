<?php

namespace Codediesel\Library\Database;

interface InterfaceDatabase
{

    /**
     * @param string $table_name
     * @param array $args
     * @return mixed
     */
    public function create(string $table_name , array $args) : mixed;

    /**
     * @param string $table_name
     * @param array $where
     * @return mixed
     */
    public function retrieve(string $table_name , array $where) : mixed;



    /**
     * @param string $table_name
     * @param array $where
     * @return mixed
     */
    public function update(string $table_name , array $arguments , array $where) : mixed;

}