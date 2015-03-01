<?php

namespace Framework\Database\Query;

use Framework\ArrayMethods as ArrayMethods;
use Framework\Base as Base;
use Framework\Database\Exception as Exception;

class Query extends Base {

    /**
     * @readwrite
     */

    protected $_connector;

    /**
     * @readwrite
     */

    protected $_from;

    /**
     * @readwrite
     */

    protected $_fields; // = array("*");

    /**
     * @readwrite
     */

    protected $_limit;

    /**
     * @readwrite
     */

    protected $_offset;

    /**
     * @readwrite
     */

    protected $_order;

    /**
     * @readwrite
     */

    protected $_direction;

    /**
     * @readwrite
     */

    protected $_join = array();

    /**
     * @readwrite
     */

    protected $_where = array();

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function from($from, $fields = array()) {
        if (empty($from)) {
            throw new Exception\Query("invalid argument");
        }
        $this->_from = $from;

        if ($fields) {
            $this->_fields[$from] = $fields;
        }
        return $this;
    }

    public function join($join, $on, $fields = array("*")) {
        if (empty($join)) {
            throw new Exception\Query("invalid argument");
        }
        if (empty($on)) {
            throw new Exception\Query("invalid argument");
        }
        $this->_fields += array($join => $fields);
        $this->_join[] = "JOIN {$join} ON {$on}";
        return $this;
    }

    public function order($order, $direction = "asc") {
        if (empty($order)) {
            throw new Exception\Query("invalid argument");
        }
        $this->_order = $order;
        $this->_direction = $direction;
        return $this;
    }

    public function where() {
        $arguments = func_get_args();

        /*
         * I added this to support the strategy from the framework
         * to use the where() instead with array("id=?", $id) with
         * where("id=?", $id, "name=?", $name)
         * AND add the array for convenience to use where(array("id=?"=>$id))
         * like in from() or all()
         */
        if (is_array($arguments[0])) {

            foreach ($arguments[0] as $clause => $value) {
                $clause = preg_replace("#\?#", "%s", $clause);
                $value = $this->_quote($value);
                $this->_where[] = sprintf($clause, $value);
            }

        } else {
            if (sizeof($arguments) < 2) {
                throw new Exception\Query("invalid argument - use one array or line up the key value pairs");
            }

            for ($i=0; $i < sizeof($arguments); $i+=2) {
                $clause = preg_replace("#\?#", "%s", $arguments[$i]);
                $value = $this->_quote($arguments[$i+1]);
                $this->_where[] = sprintf($clause, $value);
            }
        }

        return $this;
        /*

        // the original code from the book

        $arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);

        foreach (array_slice($arguments, 1, null, true) as $i => $parameter) {
            $arguments[$i] = $this->_quote($arguments[$i]);
        }
        $this->_where[] = call_user_func_array("sprintf", $arguments);
        return $this;
        */
    }

    protected function _quote($value) {

        if (is_string($value)) {
            $escaped = $this->getConnector()->escape($value);
            return "'{$escaped}'";
        }
        if (is_array($value)) {
            $buffer = array();
            foreach ($value as $i) {
                array_push($buffer, $this->_quote($i));
            }
            $buffer = join(", ", $buffer);
            return "({$buffer})";
        }
        if (is_null($value)) {
            return "NULL";
        }
        if (is_bool($value)) {
            return (int)$value;
        }
        return $this->getConnector()->escape($value);
    }

    public function save($data) {
        $isInsert = sizeof($this->_where) == 0;
        if ($isInsert) {
            $sql = $this->_buildInsert($data);
        } else {
            $sql = $this->_buildUpdate($data);
        }

//         \Framework\DebugMethods::pre($sql);

        $result = $this->getConnector()->execute($sql);


        if ($result === false) {
            throw new Exception\Query("sql");
        } else if ($isInsert) {
            return $this->getConnector()->lastInsertId;
        }
        return 0;
    }

    /**
     * alter(array(
     *  "table" => "homepage_comments",
     *  "alter" => "add",
     *  "column" => "column_name",
     *  "default" => "default null"
     * );
     *
     */
    protected function _buildAlter($data) {
        // table name
        $table = $data["table"];

        // add, modify, etc
        $action = $data["alter"];
        $columns = $data["columns"];
        $template = "ALTER TABLE %s %s %s";
        $_columns = "";
        foreach ($columns as $column => $type) {
            if (!$_columns) {
                $_columns = "{$column} {$type} DEFAULT NULL";
            } else {
                $_columns = "{$_columns}, {$column} {$type}";
            }
        }
        return sprintf($template, $table, $action, $_columns);
    }

    protected function _buildInsert($data) {

        $fields = array();
        $values = array();
        $template = "INSERT INTO %s (%s) VALUES (%s)";

        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = $this->_quote($value);
        }
        $fields = join(", ", $fields);
        $values = join(", ", $values);

        return sprintf($template, $this->from, $fields, $values);
    }

    protected function _buildUpdate($data) {
        $parts = array();
        $where = $limit = "";
        $template = "UPDATE %s SET %s %s %s";
        foreach ($data as $field => $value) {
            $parts[] = "{$field}=" . $this->_quote($value);
        }
        $parts = join(", ", $parts);
        $_where = $this->where;
        if (!empty($_where)) {
            $joined = join(", ", $_where);
            $where = "WHERE {$joined}";
        }
        $_limit = $this->limit;
        if (!empty($_limit)) {
            $_offset = $this->offset;
            $limit = "LIMIT {$_limit} {$_offset}";
        }
        return sprintf($template, $this->from, $parts, $where, $limit);
    }

    public function delete() {
        $sql = $this->_buildDelete();
        $result = $this->_connector->execute($sql);
        if ($result == false) {
            throw new Exception\Query("sql");
        }
        return $this->_connector->affectedRows;
    }

    protected function _buildDelete() {
        $where = $limit = "";
        $template = "DELETE FROM %s %s %s";
        $_where = $this->where;
        if (!empty($_where)) {
            $joined = join(", ", $_where);
            $where = "WHERE {$joined}";
        }
        $_limit = $this->limit;
        if (!empty($_limit)) {
            $_offset = $this->offset;
            $limit = "LIMIT {$_limit} {$_offset}";
        }
        return sprintf($template, $this->from, $where, $limit);
    }

    public function count() {
        $limit = $this->_limit;
        $offset = $this->_offset;
        $fields = $this->_fields;

        $this->_fields = array($this->from => array("COUNT(1)" => "rows"));
        $this->limit(1);
        $row = $this->first();
        $this->_fields = $fields;

        if ($fields) {
            $this->_fields = $fields;
        }
        if ($limit) {
            $this->_limit = $limit;
        }
        if ($offset) {
            $this->_offset = $offset;
        }
        return $row["rows"];
    }

    public function limit($limit, $page = 1) {
        if (empty($limit)) {
            throw new Exception\Query("invalid argument");
        }
        $this->_limit = $limit;
        $this->_offset = $limit * ($page - 1);
        return $this;
    }

    public function first() {
        $limit = $this->_limit;
        $offset = $this->_offset;
        $this->limit(1);
        $all = $this->all();
        $first = ArrayMethods::first($all);
        if ($limit) {
            $this->_limit = $limit;
        }
        if ($offset) {
            $this->_offset = $offset;
        }
        return $first;
    }

    public function all() {
        $sql = $this->_buildSelect();
        //echo "query:\n\n\n{$sql}\n\n\n";
        $result = $this->getConnector()->execute($sql);
        if ($result == false) {
            $error = $this->getConnector()->lastError;
            return array();
            // throw new Exception\Query("all: error with sql query");
        }
        $rows = array();
        for ($i = 0; $i < $result->num_rows; $i++) {
            $rows[] = $result->fetch_array(MYSQLI_ASSOC);
        }
        return $rows;
    }

    public function alter($what) {
        return $this->_buildAlter($what);
    }

    protected function _buildSelect() {
        $fields = array();
        $where = $order = $limit = $join = "";
        $template = "SELECT %s FROM %s %s %s %s %s";
        foreach ($this->fields as $table => $_fields) {
            foreach ($_fields as $field => $alias) {
                if (is_string($field)) {
                    $fields[] = "{$field} AS {$alias}";
                } else {
                    $fields[] = $alias;
                }
            }
        }
        $fields = join(", ", $fields);
        $_join = $this->join;
        if (!empty($_join)) {
            $join = join(", ", $_join);
        }
        $_where = $this->where;
        if (!empty($_where)) {
            $joined = join(" AND ", $_where);
            $where = "WHERE {$joined}";
        }
        $_order = $this->order;
        if (!empty($_order)) {
            $_direction = $this->direction;
            $order = "ORDER BY {$_order} {$_direction}";
        }
        $_limit = $this->limit;
        if (!empty($_limit)) {
            $_offset = $this->offset;
            if ($_offset) {
                $limit = "LIMIT {$_limit}, {$_offset}";
            } else {
                $limit = "LIMIT {$_limit}";
            }
        }
        $from = $this->from;
        return sprintf($template, $fields, $from, $join, $where, $order, $limit);
    }
}
