<?php namespace App\Helpers;

use PDO;

class Database extends PDO
{
    /**
     * @var array ensure only a single database connection
     */
    protected static $instances = array();

    /**
     * Static method get
     *
     * @param  array $group
     * @return \helpers\database
     */
    public static function get($config)
    {
        // Group information
        $type = $config['db_type'];
        $host = $config['db_host'];
        $name = $config['db_name'];
        $user = $config['db_username'];
        $pass = $config['db_password'];

        // ID for database based on the config information
        $id = "$type.$host.$name.$user.$pass";

        // Checking if the same
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }

        $instance = new Database("$type:host=$host;dbname=$name;charset=utf8", $user, $pass);
        $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Setting Database into $instances to avoid duplication
        self::$instances[$id] = $instance;

        //return the pdo instance
        return $instance;

    }

    /**
     * run raw sql queries
     * @param  string $sql sql command
     * @return none
     */
    public function raw($sql)
    {
       return $this->query($sql);
    }

    /**
     * method for selecting records from a database
     * @param  string $sql       sql query
     * @param  array  $array     named params
     * @param  object $fetchMode
     * @param  string $class     class name
     * @return array            returns an array of records
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ, $class = '')
    {
         // Append select if it isn't appended.
        if (strtolower(substr($sql, 0, 7)) !== 'select ') {
            $sql = "SELECT " . $sql;
        }

        // if value is int use datatype PARAM_INT, otherwise string
        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }

        $stmt->execute();

        // this is the returned response object
        if ($fetchMode === PDO::FETCH_CLASS) {
            return $stmt->fetchAll($fetchMode, $class);
        } else {
            return $stmt->fetchAll($fetchMode);
        }
    }

    /**
     * insert method
     * @param  string $table table name
     * @param  array $data  array of columns and values
     */
    public function insert($table, $data)
    {
        ksort($data);

        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $stmt = $this->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->lastInsertId();
    }

    /**
     * update method
     * @param  string $table table name
     * @param  array $data  array of columns and values
     * @param  array $where array of columns and values
     */
    public function update($table, $data, $where)
    {
        ksort($data);

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Delete method
     * @param  string $table table name
     * @param  array $data  array of columns and values
     * @param  array $where array of columns and values
     * @param  integer $limit default delete limit is 1, pass null to remove limit
     */
    public function delete($table, $where, $limit = 1)
    {
        ksort($where);

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }

        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");

        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }
}

/* CREDIT TO "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */
