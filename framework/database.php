<?php
namespace Framework;

use Framework\Database as Database;
use Framework\Database\Exception as Exception;

class Database extends Base {
    /**
     * @readwrite
     */
    protected $_type;
    /**
     * @readwrite
     */
    protected $_options;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {

        $type = $this->_type;

        Events::fire("framework.database.initialize.before", array($this->type, $this->options));
        if (empty($type)) {

            $configuration = Registry::get("configuration");

            if ($configuration) {
                $configuration = $configuration->initialize();

                $parsed = $configuration->parse("configuration/database");

                if (!empty($parsed->database->default) && !empty($parsed->database->default->type)) {
                    $type = $parsed->database->default->type;
                    unset($parsed->database->default->type);
                    $this->__construct(array(
                        "type" => $type,
                        "options" => (array)$parsed->database->default
                    ));
                }
            }
        }
        if (empty($type)) {
            throw new Exception("invalid type: {$type}");
        }
        Events::fire("framework.database.initialize.after", array($this->type, $this->options));
        switch ($type) {
            case "mysql":
                return new Database\Connector\MySQL($this->options);
            case "mongo":
                return new Database\Connector\Mongo($this->options);
            default:
                throw new Database\Exception\Implementation("invalid type: {$type}");
        }
    }
}
