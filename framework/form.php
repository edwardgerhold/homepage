<?php
/**
 * Edward´s Homepage is written by Edward Gerhold
 * http://github.com/edwardgerhold/homepage
 * Edward´s Homepage is originally developed for
 * http://linux-swt.de (c) 2014 Edward Gerhold
 * This is free and open source software for you.
 *
 * The Homepage Application Framework bases on the
 * "Pro PHP MVC" Framework from the namely equal book
 * by Chris Pitt released by http://apress.com.
 *
 * The application is Edward´s Homepage.
 * Load it into a PHPStorm evaluation copy from
 * http://jetbrains.com for the ultimate experience.
 *
 * Created by PhpStorm
 * Date: 21.08.14
 * Time: 20:27
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */
namespace Framework;

class Form extends Base {
    /**
     * @readwrite
     */
    protected $_elems = array();
    /**
     * @readwrite
     */
    protected $_method = "post";
    /**
     * @readwrite
     */
    protected $_action;

    /**
     * @readwrite
     */
    protected $_enctype;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function method($method) {
        $this->_method = $method;
        return $this;
    }

    public function action($action) {
        $this->_action = $action;
        return $this;
    }

    public function enctype($enctype) {
        $this->_enctype = $enctype;
        return $this;
    }

    protected function _attrs($pairs) {
        $attrs = array();
        foreach ($pairs as $key => $value) {
            if ($key != "label") {
                $attrs[] = "{$key}='{$value}'";
            }
        }
        return join(" ", $attrs);
    }

    public function label($for, $attrs = array()) {
    }

    public function input($name, $attrs = array()) {
        $label = false;

        if (isset($attrs["label"])) {
            $label = true;
            $input .= "<label>{$attrs['label']}";
        }
        $input = "<input name='{$name}'";
        if (sizeof($attrs) > 0) {
            $input .= $this->_attr($attrs);

            $input .= "/>";
            if ($label) {
                $input .= "</label>\n";
            }
            $this->_elems[] = $input;
        }
        return $this;
    }

    // i think this has to be overworked
    // have not used select lists for a long time
    // will do when i need them at once
    public function select($name, $options = array(), $attrs = array()) {

        $select = "<select name={$name}";
        if (sizeof($attrs > 0)) {
            $select .= $this->_attr($attrs);
        }
        $select .= ">\n";
        $_options = array();
        foreach ($options as $value => $option) {
            $html = "<option";
            if (is_array($option)) $html .= $this->_attr($option);
            $html .= ">{$value}</option>";
            $_options[] = $html;
        }
        $select .= join("\n", $_options) . "\n</select>";
        $this->_elems[] = $select;

        return $this;
    }

    public function get() {
        $form = "<form";

        $attrs = Markup
        if (!empty($this->_name)) {
            $form .= " method='" . $this->_method . "'";
        }
        if (!empty($this->_method)) {
            $form .= " method='" . $this->_method . "'";
        }
        if (!empty($this->_action)) {
            $form .= " action='" . $this->_action . "'";
        }
        if (!empty($this->_enctype)) {
            $form .= " enctype='" . $this->_enctype . "'";
        }
        $form .= ">\n";
        $form .= join("\n", $this->_elems) . "\n</form>\n";
        return $form;
    }
}
