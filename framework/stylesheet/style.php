<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 29.07.14
 * Time: 23:56
 */

namespace Framework\Stylesheet;

//use Framework\Element as Element;
use Framework\Stylesheet\Exception as Exception;
use \Framework\Base as Base;

class Style extends Base {
    /**
     * @read
     */
    protected $_tag = "style";
    /**
     * @readwrite
     * If a file is to be inlined
     */
    protected $_file;
    /**
     * @readwrite
     * The contents of the file to be inlined
     */
    protected $_content = "";
    /**
     * media='' Attribute
     * @readwrite
     */
    protected $_media;
    /**
     * type='' Attribute
     * @readwrite
     */
    protected $_type;
    /**
     * scoped Attribute
     * @readwrite
     */
    protected $_scoped;

    public function __construct($options = array()) {
        parent::__construct($options);
        if (isset($options["file"]) && file_exists($options["file"])) {
            ob_start();
            include($options["file"]);
            $style = ob_get_contents();
            ob_end_clean();
            $this->content = $style;
        }
    }

    protected function _attrs() {
        $attrs = array();
        if (isset($this->scoped)) {
            $attrs[] = "scoped";
        }
        if (isset($this->media)) {
            $attrs[] = sprintf("media='%s'", $this->media);
        }
        if (isset($this->type)) {
            $attrs[] = sprintf("type='%s'", $this->type);
        }
        return ((sizeof($attrs) > 0) ? " " : "") . join(" ", $attrs);
    }

    public function get() {
        $attrs = $this->_attrs();
        $content = $this->content;
        return sprintf("<style%s>%s</style>", $attrs, $content);
    }
} 