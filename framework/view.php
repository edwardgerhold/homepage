<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 11:49
 */

namespace Framework;

use Framework\Template as Template;
use Framework\View\Exception as Exception;

class View extends Base {
    /**
     * @readwrite
     */
    protected $_file;

    /**
     * @read
     */
    protected $_template;

    /**
     * @readwrite
     */
    protected $_data = array();

    /**
     * @readwrite
     *
     * I added this to keep it sightable where to exchange the template
     * or to make a template decision simpler
     */
    protected $_defaultImplementation = "\Framework\Template\Implementation\Extended";

    public function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} is not implemented");
    }

    public function _getExceptionForArgument($argument) {
        return new Exception\Argument("invalid argument");
    }

    public function __construct($options = array()) {
        parent::__construct($options);
        Events::fire("framework.view.construct.before", array($this->file));
        $implementation = $this->_defaultImplementation;
        $this->_template = new Template(array(
            "implementation" =>
                new $implementation()
        ));
        Events::fire("framework.view.construct.after", array($this->file));
    }

    public function render() {
        Events::fire("framework.view.render.before", array($this->file));
        if ($this->file != null) {
            if (!file_exists($this->file)) {
                return "<p>Error: Template not found!</p>";
            }
            return $this->template->parse(file_get_contents($this->file))->process($this->data);
        }
        return $this->template->parse("{yield action}")->process($this->data);
        // return $this->template->parse("")->process(array());
    }

    public function get($key, $default = "") {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        return $default;
    }

    protected function _set($key, $value) {
        if (!is_string($key) && !is_numeric($key)) {
            throw new View\Exception("key must be a string or number");
        }
        $data = $this->data;
        if (!$data) {
            $data = array();
        }
        $data[$key] = $value;
        $this->data = $data;
    }

    public function set($key, $value = null) {
        if (is_array($key)) { // setze ganzen array
            foreach ($key as $_key => $value) {
                $this->_set($_key, $value);
            }
            return $this;
        }
        $this->_set($key, $value); // oder nur 1 kvp
        return $this;
    }

    public function erase($key) {
        unset($this->_data[$key]);
        return $this;
    }
} 