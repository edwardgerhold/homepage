<?php

namespace Framework\Database\Connector;

use Framework\Database\Exception as Exception;

class MySQL extends Connector {
    /**
     * @readwrite
     */
    protected $_service;
    /**
     * @readwrite
     */
    protected $_host = "localhost";
    /**
     * @readwrite
     */
    protected $_username = "root";
    /**
     * @readwrite
     */
    protected $_password = "";
    /**
     * @readwrite
     */
    protected $_schema = "homepage";
    /**
     * @readwrite
     */
    protected $_port = "3306";
    /**
     * @readwrite
     */
    protected $_charset = "utf8";
    /**
     * @readwrite
     */
    protected $_engine = "InnoDB";
    /**
     * @readwrite
     */
    protected $_isConnected = false;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    protected function _isValidService() {
        $isEmpty = empty($this->_service);
        $isInstance = $this->_service instanceof \MySQLi;
        //// echo "isValidService:";
        if ($this->isConnected && $isInstance && !$isEmpty) {
            // echo "VALID";
            return true;
        }
        // echo "INVALID";
        return false;
    }

    public function connect() {
        if (!$this->_isValidService()) {
            $this->_service = new \MySQLi(
                $this->_host,
                $this->_username,
                $this->_password,
                $this->_schema,
                $this->_port
            );
            if ($this->_service->connect_error) {
                throw new Exception("unable to connect to service");
            }
            $this->_isConnected = true;
        }
        return $this;
    }

    public function disconnect() {
        if ($this->_isValidService()) {
            $this->_isConnected = false;
            $this->_service->close();
        }
        return $this;
    }

    public function query() {
        $query = new \Framework\Database\Query\Query(array(
            "connector" => $this
        ));
        return $query;
    }

    public function sync($model) {
        // echo "sync start";
        $lines = array();
        $indices = array();
        $columns = $model->getColumns();
        $template = "CREATE TABLE %s (\n%s,\n%s\n) ENGINE=%s DEFAULT CHARSET=%s;";
        foreach ($columns as $column) {
            $raw = $column["raw"];
            $name = $column["name"];
            $type = $column["type"];
            $length = $column["length"];
            if ($column["primary"]) {
                $indices[] = "PRIMARY KEY ({$name})";
            }
            if ($column["index"]) {
                $indices[] = "KEY {$name} ({$name})";
            }
            switch ($type) {
                case "autonumber":
                    $lines[] = "{$name} int(11) NOT NULL AUTO_INCREMENT";
                    break;
                case "text":
                    if ($length !== null && $length <= 255) {
                        $lines[] = "{$name} varchar({$length}) DEFAULT NULL";
                    } else {
                        $lines[] = "{$name} text";
                    }
                    break;
                case "integer":
                    $lines[] = "{$name} int(11) DEFAULT NULL";
                    break;
                case "decimal":
                    $lines[] = "{$name} float DEFAULT NULL";
                    break;
                case "boolean":
                    $lines[] = "{$name} tinyint(4) DEFAULT NULL";
                    break;
                case "datetime":
                    $lines[] = "{$name} datetime DEFAULT NULL";
                    break;
                case "date":
                    $lines[] = "{$name} date DEFAULT NULL";
                    break;
                case "time":
                    $lines[] = "{$name} time DEFAULT NULL";
                    break;
            }
        }
        $table = $model->table;
        $sql = sprintf(
            $template,
            $table,
            join(",\n", $lines),
            join(",\n", $indices),
            $this->_engine,
            $this->_charset
        );

        $result = $this->execute("DROP TABLE IF EXISTS {$table};");
        if ($result === false) {
            $error = $this->lastError;
            throw new Exception\Sql("there was an error in the query: {$error}");
        }
        $result = $this->execute($sql);
        if ($result == false) {
            $error = $this->lastError;
            throw new Exception\Sql("there was an error in the query: {$error}");
        }
        // echo "sync end";
        return $this;
    }

    public function execute($sql) {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        //echo $sql;
        $result = $this->_service->query($sql);

        return $result;
    }

    public function escape($value) {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        return $this->_service->real_escape_string($value);
    }

    public function getLastInsertId() {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        return $this->_service->insert_id;
    }

    public function getLastError() {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        return $this->_service->error;
    }

    public function getAffectedRows() {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        return $this->_service->affected_rows;
    }
} 