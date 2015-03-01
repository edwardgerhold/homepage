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
* Following more rules, the page is also developed with 
* the HTML5 Cookbook by T. Leadbetter and C. Hudson
* and by Responsive Webdesign (german) by C. Zillgens
*
* Created by PhpStorm.
* User: Edward Gerhold
* Date: 04.09.14
* Time: 13:35
* Project: Edward´s Homepage
*/

namespace Homepage;


class MagicController extends Controller {
    /**
     * @read
     */
    protected $_isMagic = true;


    protected function _getDefaultView($action) {
        return strtolower(get_class($this)) . "/" . $action . ".html";
    }

    public function __get($action) {
        $arguments = func_get_args();

        $file = $this->_defaultPath."/".$this->_getDefaultView($action);
        if (file_exists($file)) {
            return file_get_contents($file);
        }
        return call_user_func_array("parent::__get", $arguments);
    }

    public function __call($action, $arguments) {
        $arguments = func_get_args();
        $file = $this->_defaultPath."/".$this->_getDefaultView($action);
        echo $file;
        if (file_exists($file)) {
            // Behave like the action was called (render() should follow)
            return;
        }
        return call_user_func_array("parent::__call", $arguments);
    }

} 