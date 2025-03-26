<?php

namespace Codediesel\Library\Database\Type;


use Codediesel\Library\Database\AbstractDatabase;
use Codediesel\Library\Database\InterfaceDatabase;

class Mysql extends AbstractDatabase implements InterfaceDatabase
{


    public function __construct()
    {
        $this->dns = sprintf("mysql:host=%s;port=%s;dbname=%s"
            , $_ENV['DATABASE_LOCALHOST']
            , 3306
            , $_ENV['DATABASE_NAME']
        );
        $this->username = $_ENV['DATABASE_USERNAME'];
        $this->password = $_ENV['DATABASE_PASSWORD'];
        $this->options = [];

        $this->conn = new \PDO($this->dns, $this->username, $this->password, $this->options);
    }

    /**
     * @param string $table_name
     * @param array $args
     * @return mixed
     */
    public function create(string $table_name, array $args): mixed
    {
        try {

            $sql = <<<SQL
insert into $table_name (%s) values (%s);
SQL;

            $this->prepare($sql, $args);
            if ($this->isAdded()) {

                return $this->retrieve($table_name, [
                    'id' => $this->conn->lastInsertId()
                ]);
            }
            return false;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /**
     * @param string $table_name
     * @param array $args
     * @param array $where
     * @return array
     */
    public function update(string $table_name , array $args , array $where) : array
    {
        try {


            $sql = <<<SQL
update $table_name set %s where %s;
SQL;

            $this->prepare($sql, $args);
            if ($this->isAdded()) {

                return $this->retrieve($table_name, $where);
            }
            return [];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * @param string $table_name
     * @param array $where
     * @return array
     */
    public function retrieve(string $table_name, array $where): array
    {
        try {
            $sql = <<<SQL
select * from  $table_name %s;
SQL;

            $prepare = $this->prepare($sql, $where, true);
            return (array) $prepare->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /** @howtouse used to Retrieve All records
     * @param string $table_name
     * @param array $where
     * @return array
     */
    public function retrieveRecords(string $table_name, array $where): array
    {
        try {
            $sql = <<<SQL
select * from  $table_name %s;
SQL;

            $prepare = $this->prepare($sql, $where, true);
            return $prepare->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        } catch (\Throwable $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
}