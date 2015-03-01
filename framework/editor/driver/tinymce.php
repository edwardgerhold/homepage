<?php
/**
*
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
* Date: 24.08.14
* Time: 12:36
* User: Edward Gerhold
* Project Edward´s Homepage
*/


namespace Framework\Editor\Driver;

use \Framework\Editor as Editor;

class TinyMCE extends Editor\Driver {

    /**
     * @readwrite
     */
    protected $_inline = false;
    /**
     * @readwrite
     */
    protected $_selector = "textarea";
    /**
     * @readwrite
     */
    protected $_script = "/scripts/tinymce/tinymce.min.js";

    public function initialize() {
	    return $this;
    }

    protected function _buildLoad() {
        $selector = $this->_selector;
        $script = $this->_script;
        $load = "<script src='{$script}'></script>";
        $init = "<script>tinymce.init({ selector: '{$selector}' });</script>";
        return $load.$init;
    }

    protected function _buildInline() {
        $selector = $this->_selector;
        $script = WEB_PATH . $this->_script;
        $template = "<script>%s</script>";
        if (file_exists(script)) {
            $load = sprintf($template, file_get_contents($script));
        }
        $init = "<script>tinymce.init({ selector: '{$selector}' });</script>";
        return $load.$init;
    }

    public function get() {
        $inline = $this->_inline;
        if ($inline) {
            return $this->_buildInline();
        } else {
            return $this->_buildLoad();
        }
    }
}