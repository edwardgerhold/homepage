<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 20:34
 */
namespace Framework;

use Framework\Configuration as Configuration;
use Framework\Configuration\Exception as Exception;

class Configuration extends Base {

    /**
     * @readwrite
     */
    protected $_type;
    /**
     * @readwrite
     */
    protected $_options;

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} not implemented");
    }

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        Events::fire("framework.configuration.initialize.before", array($this->type, $this->options));
        if (!$this->_type) {
            throw new Exception("invalid configuration type");
        }
        Events::fire("framework.configuration.initialize.after", array($this->type, $this->options));
        switch ($this->_type) {
            case "ini":
                return new Configuration\Driver\Ini($this->options);
            case "json":
                return new Configuration\Driver\Json($this->options);
            case "xml":
                return new Configuration\Driver\Xml($this->options);
            case "database":
                return new Configuration\Driver\Database($this->options);
            default:
                throw new Exception\Argument("invalid configuration type");
        }
    }
}
