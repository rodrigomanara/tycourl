<?php

namespace Codediesel\Library\Database;

abstract class AbstractDatabase implements InterfaceDatabase
{
    protected \PDO $conn;
    protected string $dns;
    protected string $username;
    protected string $password;
    protected array $options;
    /**
     * @var true
     */
    private bool $isAdded = false;

    /**
     * @param string $table_name
     * @param array $args
     * @return mixed
     */
    abstract public function create(string $table_name, array $args): mixed;

    /**
     * @param string $table_name
     * @param array $where
     * @return mixed
     */
    abstract public function retrieve(string $table_name, array $where): mixed;

    /**
     * @param array $args
     * @return string
     */
    public static function link(array $args, $asc = false): string
    {
        if (!$asc)
            return sprintf("%s", implode(",", $args));

        return sprintf("`%s`", implode("`,`", $args));
    }

    /**
     * @param string $sql
     * @param array $args
     * @return false|\PDOStatement
     */
    public function prepare(string $sql, array $args, bool $where = false, array $options = []): bool|\PDOStatement
    {

        $keys = [];
        $prep = [];
        $combine = [];
        if (!empty($args)) {
            $keys = array_keys($args);
            $prep = array_map(function ($key) {
                return sprintf(":%s", $key);
            }, $keys);
            $query = sprintf($sql, static::link($keys, true), static::link($prep));
            $values = array_values($args);
            $combine = array_combine($prep, $values);
        }

        if ($where) {
            $and = [];
            foreach (array_combine($keys, $prep) as $key => $value) {
                $and[] = "`$key` = $value";
            }
            $merge = '';
            if (!empty($and))
                $merge = sprintf(" and %s", implode(" and ", $and));
            $query = sprintf($sql, sprintf("where 0=0 %s", $merge));


            if ($options) {

                //check if is order by
                if ($options["orderBy"]) {
                    $merge = sprintf("%s order by %s %s", $merge, $options["orderBy"]['field'],
                        $options["orderBy"]['action']);
                    $query = sprintf($sql, sprintf("where 0=0 %s", $merge));
                }
            }
        }


        $prepare = $this->conn->prepare($query);
        if ($prepare->execute($combine))
            $this->isAdded = true;

        return $prepare;
    }

    /**
     * @param string $sql
     * @param array $args
     * @param array $where
     * @return bool|\PDOStatement
     */
    public function prepareWhere(string $sql, array &$args, array &$where): bool|\PDOStatement
    {
        $whereCombine = [];
        $query = null;

        $keys = array_keys($args);
        $prep = array_map(function ($key) {
            return sprintf(":%s", $key);
        }, $keys);


        $values = array_values($args);
        $combine = array_combine($prep, $values);
        $update = array_map(function ($key) {
            return sprintf("`%s` = :%s", $key, $key);
        }, $keys);
        $update = implode(",", $update);

        if ($where) {
            $whereKeys = array_keys($where);
            $wherePrep = array_map(function ($whereKeys) {
                return sprintf(":%s", $whereKeys);
            }, $whereKeys);

            $and = [];
            foreach (array_combine($whereKeys, $wherePrep) as $key => $value) {
                $and[] = "`$key` = $value";
            }
            $merge = implode(" and ", $and);
            $query = sprintf($sql, $update, sprintf(" 0=0 and %s", $merge));

            $values = array_values($where);
            $whereCombine = array_combine($wherePrep, $values);
        }
        $merge = array_merge($combine, $whereCombine);

        $prepare = $this->conn->prepare($query);
        if ($prepare->execute($merge))
            $this->isAdded = true;

        return $prepare;
    }

    /**
     * @param $sql
     * @param $where
     * @return bool
     */
    protected function executeQuery($sql, $where): bool
    {


        $keys = array_keys($where);
        $prep = array_map(function ($key) {
            return sprintf(":%s", $key);
        }, $keys);

        $query = sprintf($sql, static::link($keys, true)
            , static::link($prep));

        $values = array_values($where);
        $combine = array_combine($prep, $values);

        if ($where) {

            $whereKeys = array_keys($where);
            $wherePrep = array_map(function ($key) {
                return sprintf(":%s", $key);
            }, $keys);

            $and = [];
            foreach (array_combine($whereKeys, $wherePrep) as $key => $value) {
                $and[] = "`$key` = $value";
            }

            $merge = implode(" and ", $and);
            $query = sprintf($sql, sprintf("where 0=0 and %s", $merge));
        }

        $prepare = $this->conn->prepare($query);
        if ($prepare->execute($combine))
            $this->isAdded = true;

        return $this->isAdded;
    }

    protected function isAdded(): bool
    {
        return $this->isAdded;
    }
}