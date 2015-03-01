<?php
namespace Framework;

use Framework\Javascript\Exception as Exception;

class Javascript extends Base {

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
        Events::fire("framework.javascript.initialize.before", array($this->type, $this->options));
        if (!$this->type) {
            $configuration = Registry::get("configuration");
            if ($configuration) {
                $configuration = $configuration->initialize();
                $parsed = $configuration->parse("configuration/javascript");
                if (!empty($parsed->javascript->default->type)) {
                    // INI: javascript.default.type =
                    // JSON: { "javascript": { "default": { "type": "" } } }
                    $this->type = $parsed->javascript->default->type;
                    $this->options = (array)$parsed->javascript->default;
                }
            }
            throw new Exception\Implementation("invalid javascript driver type");
        }
        Events::fire("framework.javascript.initialize.after", array($this->type, $this->options));
        switch ($this->type) {
            case "server":
                return new Javascript\Driver\Server($this->options);
            default:
                throw new Exception\Implementation("invalid javascript driver type");
        }
    }
}

