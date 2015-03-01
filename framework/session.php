<?php
namespace Framework;

use Framework\Session\Exception as Exception;

class Session extends Base {

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
        Events::fire("framework.session.initialize.before", array($this->type, $this->options));

        if (!$this->_type) {
            $configuration = Registry::get("configuration");

            if ($configuration) {
                $configuration = $configuration->initialize();
                $parsed = $configuration->parse("configuration/session");

                if (!empty($parsed->session->default) && !empty($parsed->session->default->type)) {
                    $this->type = $parsed->session->default->type;
                    unset($parsed->session->default->type);
                    $this->options = (array)$parsed->session->default;
                }
            }
        }

        if (!$this->_type) {
            throw new Exception\Argument("invalid type");
        }

        Events::fire("framework.session.initialize.after", array($this->type, $this->options));

        switch ($this->_type) {
            case "server":
                return new \Framework\Session\Driver\Server($this->options);
                break;
            default:
                throw new Exception\Argument("invalid type");
                break;
        }
    }
}