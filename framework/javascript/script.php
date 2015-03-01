<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 26.07.14
 * Time: 11:37
 */

namespace Framework\Javascript;
use \Framework\Base as Base;
//use Framework\Element as Element;
use Framework\Javascript\Exception as Exception;

class Script extends Base {
    /**
     * @read
     */
    protected $_tag = "script";
    /**
     * @readwrite
     */
    protected $_async;
    /**
     * @readwrite
     */
    protected $_defer;
    /**
     * @readwrite
     */
    protected $_type;
    /**
     * @readwrite
     */
    protected $_src;

    /**
     * @readwrite
     */
    protected $_code = "";

    /**
     * @readwrite
     */
    protected $_onload;
    /**
     * @readwrite
     */
    protected $_onerror;

    /**
     * @readwrite
     */
    protected $_file;

    /**
     * @param array $options
     *
     * "file" => "syntax.js"
     * will load the file into the tag
     * to inline
     */
    public function __construct($options = array()) {
        parent::__construct($options);
        if (isset($options["file"]) && file_exists($options["file"])) {
            ob_start();
            include($options["file"]);
            $script = ob_get_contents();
            ob_end_clean();
            $this->code = $script;
        }
    }

    public function _attrs() {
        $attrs = array();
        if (isset($this->defer)) {
            $attrs[] = "defer";
        }
        if (isset($this->async)) {
            $attrs[] = "async";
        }
        if (isset($this->id)) {
            $attrs[] = sprintf("id='%s'", $this->id);
        }
        if (isset($this->class)) {
            $attrs[] = sprintf("class='%s'", $this->class);
        }
        if (isset($this->src) && empty($this->code)) {
            $attrs[] = sprintf("src='%s'", $this->src);
        }
        if (isset($this->type)) {
            $attrs[] = sprintf("type='%s'", $this->type);
        }
        if (isset($this->onload)) {
            $attrs[] = sprintf("onload='%s'", $this->onload);
        }
        if (isset($this->onerror)) {
            $attrs[] = sprintf("onerror='%s'", $this->onerror);
        }
        return (sizeof($attrs) ? " " : "") . join(" ", $attrs);
    }

    public function get() {
        $attrs = $this->_attrs();
        $code = $this->code;
        $script = "<script%s>%s</script>";
        return sprintf($script, $attrs, $code);
    }

} 