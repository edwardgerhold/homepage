<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 10:58
 */

namespace Framework;

use Framework\Model\Exception as Exception;

class Model extends Base {
    /**
     * @readwrite
     */
    protected $_table;
    /**
     * @readwrite
     */
    protected $_connector;
    /**
     * @read
     */
    protected $_types = array("autonumber", "text", "integer", "decimal", "boolean", "datetime", "date", "time");

    /**
     * @readwrite
     */
    protected $_columns = array();
    /**
     * @readwrite
     */
    protected $_primary;
    /**
     * @readwrite
     */
    protected $_errors = array();

    /**
     * @readwrite
     */
    protected $_validators = array(
        "required" => array(
            "handler" => "_validateRequired",
            "message" => "The {0} field is required"
        ),
        "alpha" => array(
            "handler" => "_validateAlpha",
            "message" => "The {0} field can only contain letters"
        ),
        "numeric" => array(
            "handler" => "_validateNumeric",
            "message" => "The {0} field can only contain numbers"
        ),
        "alphanumeric" => array(
            "handler" => "_validateAlphaNumeric",
            "message" => "The {0} field can only contain numbers and letters"
        ),
        "max" => array(
            "handler" => "_validateMax",
            "message" => "The {0} field must contain less than {2} characters"
        ),
        "min" => array(
            "handler" => "_validateMin",
            "message" => "The {0} field must contain more than {2} characters"
        ),

        "email" => array(
            "handler" => "_validateEmail",
            "message" => "The {0} field must contain a valid email address."
        )
    );

    public function _getExceptionForImplementation($method) {
        return new Exception("{$method} is not implemented");
    }

    public function __construct($options = array()) {
        parent::__construct($options);
        $this->load();
    }
e
    public function load() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        $name = $primary["name"];
        if (!empty($this->$raw)) {
            $previous = $this->getConnector()
                ->query()
                ->from($this->table)
                ->where("{$name}=?", $this->$raw)
                ->first();
            if ($previous == null) {
                throw new Exception("Primary key invalid");
            }
            foreach ($previous as $key => $value) {
                $prop = "_{$key}";
                if (!empty($previous->$key) && !isset($prop)) {
                    $this->$key = $previous->$key;
                }
            }
        }
    }

    public function delete() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        //$name = $primary["name"];
        $id = $this->_id;
        if (!empty($this->$raw)) {
            //return $this->getConnector()->query()->from($this->table)->where("{$name}=?", $this->$raw)->delete();
            return $this->getConnector()->query()->from($this->_table)->where("id=?", $id)->delete();
        }
        return null;
    }

    public static function deleteAll($where = array()) {
        $instance = new static();
        $query = $instance->getConnector()->query()->from($instance->table);
        foreach ($where as $clause => $value) {
            $query->where($clause, $value);
        }
        return $query->delete();
    }

    public function save() {

        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        $name = $primary["name"];

        $query = $this->getConnector();
        $query = $query->query();
        $query = $query->from($this->_table);

        if (!empty($this->$raw)) {
            $query->where("{$name}=?", $this->$raw);
        }

        $data = array();

        foreach ($this->columns as $key => $column) {
            if (!$column["read"]) {
                $prop = $column["raw"];
                $data[$key] = $this->$prop;
                continue;
            }
            if (($column != $this->getPrimaryColumn()) && $column) {
                $method = "get" . ucfirst($key);
                $data[$key] = $this->$method();
                continue;
            }
        }
        $result = $query->save($data);
        if (isset($result)) {
            $this->$raw = $result;
        }

        //  echo "save done";
        return $result;
    }

    public function getTable() {
        if (empty($this->_table)) {
            $this->_table = strtolower(StringMethods::singular(get_class($this)));
        }
        return $this->_table;
    }

    public function getConnector() {
        if (empty($this->_connector)) {
            $database = Registry::get("database");
            if (!$database) {
                //echo "exception cant get connector";
                throw new Exception("can't get a connector");
            }
            $this->_connector = $database->initialize();
        }
        return $this->_connector;
    }

    public function getColumns() {
        if (empty($this->_columns)) {
            $primaries = 0;
            $columns = array();
            $class = get_class($this);
            $types = $this->_types;
            $inspector = new Inspector($this);
            $properties = $inspector->getClassProperties();
            $first = function ($array, $key) {
                if (!empty($array[$key]) && sizeof($array[$key]) == 1) {
                    return $array[$key][0];
                }
                return null;
            };
            foreach ($properties as $property) {
                $propertyMeta = $inspector->getPropertyMeta($property);
                if (!empty($propertyMeta["@column"])) {
                    $name = preg_replace("#^_#", "", $property);
                    $primary = !empty($propertyMeta["@primary"]);
                    $type = $first($propertyMeta, "@type");
                    $length = $first($propertyMeta, "@length");
                    $index = !empty($propertyMeta["@index"]);
                    $readwrite = !empty($propertyMeta["@readwrite"]);
                    $read = !empty($propertyMeta["@read"]) || $readwrite;
                    $write = !empty($propertyMeta["@write"]) || $readwrite;
                    $validate = !empty($propertyMeta["@validate"]) ? $propertyMeta["@validate"] : false;
                    $label = $first($propertyMeta, "@label");
                    if (!in_array($type, $types)) {
                        throw new Exception("{$type} is not a valid type");
                    }
                    if ($primary) $primaries = $primaries + 1;
                    $columns[$name] = array(
                        "raw" => $property,
                        "name" => $name,
                        "primary" => $primary,
                        "type" => $type,
                        "length" => $length,
                        "index" => $index,
                        "read" => $read,
                        "write" => $write,
                        "validate" => $validate,
                        "label" => $label
                    );
                }
            }
            if ($primaries !== 1) {
                throw new Exception("{$class} must have exactly one primary column.");
            }
            $this->_columns = $columns;
        }
        return $this->_columns;
    }

    public function getColumn($name) {
        if (!empty($this->_columns[$name])) {
            return $this->_columns[$name];
        }
        return null;
    }

    public function getPrimaryColumn() {
        if (!isset($this->_primary)) {
            $primary = null;
            foreach ($this->_columns as $column) {
                if (!empty($column["primary"])) {
                    $primary = $column;
                    break;
                }
            }
            $this->_primary = $primary;
        }
        return $this->_primary;
    }

    public static function first($where = array(), $fields = array("*"), $order = null, $direction = null) {
        $model = new static();
        return $model->_first($where, $fields, $order, $direction);
    }

    protected function _first($where = array(), $fields = array("*"), $order = null, $direction = null) {
        $query = $this->getConnector()->query()->from($this->table, $fields);
        foreach ($where as $clause => $value) {
            $query->where($clause, $value);
        }
        if ($order != null) {
            $query->order($order, $direction);
        }
        $first = $query->first();
        $class = get_class($this);
        if ($first) {
            return new $class($query->first());
        }
        return null;
    }

    public static function all($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $page = null) {
        $model = new static();
        return $model->_all($where, $fields, $order, $direction, $limit, $page);
    }

    protected function _all($where = array(), $fields = array("*"), $order = null, $direction = null, $limit = null, $page = null) {
        $query = $this->getConnector();
        $query = $query->query()->from($this->_table, $fields);
        // echo "all die zweite";
        foreach ($where as $clause => $value) {
            $query->where($clause, $value);
        }
        if ($order != null) {
            $query->order($order, $direction);
        }
        if ($limit != null) {
            $query->limit($limit, $page);
        }
        $rows = array();
        $class = get_class($this);
        foreach ($query->all() as $row) {
            $rows[] = new $class($row);
        }
        return $rows;
    }

    public static function count($where = array()) {
        $model = new static();
        return $model->_count($where);
    }

    protected function _count($where = array()) {
        $query = $this->getConnector()->query()->from($this->table);
        foreach ($where as $clause => $value) {
            $query->where($clause, $value);
        }
        return $query->count();
    }

    protected function _validateRequired($value) {
        return !empty($value);
    }

    protected function _validateAlpha($value) {
        return StringMethods::match($value, "#^([a-zA-Z]+)$#");
    }

    protected function _validateNumeric($value) {
        return StringMethods::match($value, "#^([0-9]+)$#");
    }

    protected function _validateAlphaNumeric($value) {
        return StringMethods::match($value, "#^([a-zA-Z0-9]+)$#");
    }

    protected function _validateMax($value) {
        return strlen($value) <= (int)$number;
    }

    protected function _validateMin($value) {
        return strlen($value) >= (int)$number;
    }

    protected function _validateEmail($value) {
        return StringMethods::match($value, "#^([\\S]+@[\\S]+)$#");
    }

    public function validate() {
        $this->_errors = array();
        $columns = $this->getColumns();
        foreach ($columns as $column) {
            if ($column["validate"]) {
                $pattern = "#[a-z]+\\(([a-zA-Z0-9, ]+)\\)#";
                $raw = $column["raw"];
                $name = $column["name"];
                $validators = $column["validate"];
                $label = $column["label"];
                $defined = $this->getValidators();
                foreach ($validators as $validator) {
                    $function = $validator;
                    $arguments = array(
                        $this->$raw
                    );
                    $match = StringMethods::match($validator, $pattern);
                    if (count($match) > 0) {
                        $matches = StringMethods::split($match[0], ",\s*");
                        $arguments = array_merge($arguments, $matches);
                        $offset = StringMethods::indexOf($validator, "(");
                        $function = substr($validator, 0, $offset);
                    }
                    if (!isset($defined[$function])) {
                        throw new Exception("The {$function} validator is not defined");
                    }
                    $template = $defined[$function];
                    if (!call_user_func_array(array($this, $template["handler"]), $arguments)) {
                        $replacements = array_merge(array(
                            $label ? $label : $raw
                        ), $arguments);
                        $message = $template["message"];
                        foreach ($replacements as $i => $replacement) {
                            $message = str_replace("{{$i}}", $replacement, $message);
                        }
                        if (!isset($this->_errors[$name])) {
                            $this->_errors[$name] = array();
                        }
                        $this->_errors[$name][] = $message;
                    }
                }
            }
        }
        return !sizeof($this->errors);
    }


    // returns null if the property name is no @column
    public function getColumnMeta($columnName) {
        $inspector = $this->_inspector;
        $meta = $inspector->getPropertyMeta($columnName);
        if (isset($meta["@column"])) {
            return $meta;
        }
        return null;
    }
    // if the property is a @column, add to an array, and return the array
    public function getColumnNames() {
        $columns = array();
        $inspector = $this->_inspector;
        $allProperties = $inspector->getClassProperties();
        foreach ($allProperties as $property) {
            $meta = $inspector->getPropertyMeta($property);
            if (isset($meta["@column"])) {
                $columns[] = $property;
            }
        }
        return $columns;
    }
    // The static function returns all columns with meta
    static public function getAllColumnMeta() {
        $meta = array();
        $model = new static();
        $names = $model->getColumnNames();
        foreach ($names as $name) {
            $meta[] = $model->getColumnMeta($name);
        }
        return $meta;
    }
    // This function returns true if a property is a @column
    static public function hasColumn($name) {
        $model = new static();
        $meta = $model->getPropertyMeta($name);
        return isset($meta["@column"]);
    }
}