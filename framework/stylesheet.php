<?php
namespace Framework;

use Framework\Stylesheet\Exception as Exception;

class Stylesheet extends Base {

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
        if (!$this->type) {
            $configuration = Registry::get("configuration");
            if ($configuration) {
                $configuration = $configuration->initialize();
                $parsed = $configuration->parse("configuration/stylesheet");
                if (!empty($parsed->javascript->default->type)) {
                    $this->type = $parsed->javascript->default->type;
                    $this->options = (array)$parsed->javascript->default;
                }
            }
            throw new Exception\Implementation("invalid stylesheet driver type");
        }
        switch ($this->type) {
            case "css":
                return new Stylesheet\Driver\Css($this->options);
            default:
                throw new Exception\Implementation("invalid stylesheet driver type");
        }
    }
}

