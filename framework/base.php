<?php
namespace Framework;

use Framework\Core\Exception as Exception;

class Base {
    /**
     * @var \Framework\Inspector
     *
     * @readwrite
     *
     */
    protected $_inspector;

    /**
     * @param array $options
     */
    function __construct($options = array()) {
        $this->_inspector = new Inspector($this);
        if (is_array($options) || is_object($options)) {
            foreach ($options as $key => $value) {
                $key = ucfirst($key);
                $method = "set{$key}";
                $this->$method($value);
            }
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this|mixed|null
     * @throws ReadOnly
     * @throws Implementation
     * @throws Core\Exception
     */
    public function __call($name, $arguments) {
        if (empty($this->_inspector)) {
            throw new Exception("Call parent::__construct!!!");
        }
        $getMatches = StringMethods::match($name, "^get([a-zA-Z0-9]+)$");
        if (sizeof($getMatches) > 0) {
            $normalized = lcfirst($getMatches[0]);
            $property = "_{$normalized}";
            if (property_exists($this, $property)) {
                $meta = $this->_inspector->getPropertyMeta($property);
                if (empty($meta["@readwrite"]) && empty($meta["@read"])) {
                    throw $this->_getExceptionForReadOnly($normalized);
                }
                if (isset($this->$property)) {
                    return $this->$property;
                }
                return null;
            }
        }
        $setMatches = StringMethods::match($name, "^set([a-zA-Z0-9]+)$");
        if (sizeof($setMatches) > 0) {
            $normalized = lcfirst($setMatches[0]);
            $property = "_{$normalized}";
            if (property_exists($this, $property)) {
                $meta = $this->_inspector->getPropertyMeta($property);
                if (empty($meta["@readwrite"]) && empty($meta["@read"])) {
                    throw $this->_getExceptionForReadOnly($normalized);
                }
                $this->$property = $arguments[0];
                return $this;
            }
        }

        throw $this->_getExceptionForImplementation($name);
    }

    /**
     * @param $prop
     * @return mixed
     */
    public function __get($prop) {
        $function = "get" . ucfirst($prop);
        return $this->$function($prop);
    }

    /**
     * @param $prop
     * @param $value
     * @return mixed
     */
    public function __set($prop, $value) {
        $function = "set" . ucfirst($prop);
        return $this->$function($value);
    }

    /**
     * @return ReadOnly
     */
    protected function _getExceptionForReadOnly() {
        return new Exception\ReadOnly("readonly");
    }

    /**
     * @return WriteOnly
     */
    protected function _getExceptionForWriteOnly() {
        return new Exception\WriteOnly("writeonly");
    }

    /**
     * @return Property
     */
    protected function _getExceptionForProperty() {
        return new Exception\Property("invalid property");
    }

    /**
     * @return Implementation
     */
    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("invalid implementation for {$method}");
    }

    public function __destruct() {
    }

    public function __toString() {
        return "[[PHPobject ".get_class($this)."]]";
    }
}

?>