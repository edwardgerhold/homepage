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
* Created by PhpStorm
* Date: 29.08.14
* Time: 22:27
* User: Edward Gerhold
* Project Edward´s Homepage
*/


namespace Framework;

class Visitor extends Base {
    /**
     * @readwrite
     */
    protected $_func;

    function __construct($options) {
        parent::__construct(array());
    }

    public function visit() {
        $arguments = func_get_args();
        $func = $this->_func;
        return call_user_func_array($func, $arguments);
    }

} 