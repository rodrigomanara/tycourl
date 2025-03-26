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
    public static function link(array $args, $asp = false): string
    {
        if (!$asp)
            return sprintf("%s", implode(",", $args));

        return sprintf("`%s`", implode("`,`", $args));
    }

    /**
     * @param string $sql
     * @param array $args
     * @return false|\PDOStatement
     */
    public function prepare(string $sql, array $args, bool $where = false): bool|\PDOStatement
    {
        $keys = array_keys($args);
        $prep = array_map(function ($key) {
            return sprintf(":%s", $key);
        }, $keys);

        $query = sprintf($sql, static::link($keys, true)
            , static::link($prep));

        $values = array_values($args);
        $combine = array_combine($prep, $values);

        if ($where) {
            $and = [];
            foreach (array_combine($keys, $prep) as $key => $value) {
                $and[] = "`$key` = $value";
            }

            $merge = implode(" and ", $and);
            $query = sprintf($sql, sprintf("where 0=0 and %s", $merge));
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
    public function prepareWhere(string $sql, array $args, array $where ): bool|\PDOStatement
    {
        $keys = array_keys($args);
        $prep = array_map(function ($key) {
            return sprintf(":%s", $key);
        }, $keys);

        $query = sprintf($sql, static::link($keys, true)
            , static::link($prep));

        $values = array_values($args);
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

        return $prepare;
    }

    protected function isAdded(): bool
    {
        return $this->isAdded;
    }
}