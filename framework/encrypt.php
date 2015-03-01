<?php
namespace Framework;

use Framework\Encrypt\Exception as Exception;

class Encrypt extends Base {

    /**
     * @readwrite
     */
    protected $_type;

    /**
     * @readwrite
     */
    protected $_options = array();

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        if (!$this->_type) {
            // configuration is only read from disk iff type is not provided via options.
            $configuration = Registry::get("configuration");
            if ($configuration) {
                $configuration = $configuration->initialize();
                $parsed = $configuration->parse("configuration/encrypt");
                if (!empty($parsed->encrypt->default->type)) {
                    $this->type = $parsed->encrypt->default->type;
                    $this->options = (array)$parsed->encrypt->default;
                }
            }
            throw new Exception\Implementation("invalid encryption driver type");
        }
        switch ($this->type) {
            case "md5":
                return new Encrypt\Driver\MD5($this->options);
            case "blowfish":
            case "rsa":
            case "sha":
            default:
                throw new Exception\Implementation("invalid encryption driver type");
        }
    }
}

