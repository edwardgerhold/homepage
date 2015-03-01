<?php
namespace Framework;

use Framework\Cookie\Exception as Exception;

class Cookie extends Base {
    /**
     * @readwrite
     */
    protected $_type;
    /**
     * @readwrite
     */
    protected $_options;

    function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        Events::fire("framework.cookie.initialize.before", array($this->type, $this->options));

        if (!$this->_type) {
            $configuration = Registry::get("configuration");
            if ($configuration) {
                $configuration = $configuration->initialize();
                $parsed = $configuration->parse("configuration/cookie");
                if (!empty($parsed->cookie->default) && !empty($parsed->cookie->default->type)) {
                    $this->type = $parsed->cookie->default->type;
                    unset($parsed->cookie->default->type);
                    $this->options = (array)$parsed->cookie->default;
                }
            }
        }

        if (!$this->_type) {
            throw new Exception\Argument("invalid type");
        }

        Events::fire("framework.cookie.initialize.after", array($this->type, $this->options));

        switch ($this->_type) {
            case "server":
                return new \Framework\Cookie\Driver\Server($this->options);
                break;
            default:
                throw new Exception\Argument("invalid type");
                break;
        }
    }
}