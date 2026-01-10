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
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function retrieveRecords(string $table_name , array $where , int $limit , int $offset , array $options = []) : mixed;

    /**
        * @param string $table_name
        * @param array $arguments
        * @param array $where
        * @return mixed
        */
        
    public function update(string $table_name , array $arguments , array $where) : mixed;


    /**
     * @param string $table_name
     * @param array $where
     * @return mixed
     */
    public function delete(string $table_name , array $where) : mixed;

    /**
     * @param string $sql
     * @param array $args
     * @return mixed
     */
    public function rawSql(string $sql , array $args = [] ) : mixed;

}