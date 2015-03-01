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
* Date: 03.09.14
* Time: 22:54
* Project: Edward´s Homepage
*/
use Framework\Registry as Registry;
class Webgl extends Homepage\Controller {

    function cube($id = null) {

        $this->_loadViewFromSubDirectory($id);
    }
    function three($id = null) {
        if (!$this->_loadViewFromSubDirectory($id)) {
            $this->_includeLinkListOfSubDirectory();
        }
    }
    function collada($id) {
        if (!$this->_loadViewFromSubDirectory($id)) {
        }
    }
    function editor() {
        if (!$this->_loadViewFromSubDirectory($id)) {
        }
    }
    function guide($id = null) {
        if ($this->_loadViewFromSubDirectory($id)) {
            $this->_willRenderLayoutView = false;
        }
    }
    function shading($id = null) {
        if ($this->_loadViewFromSubDirectory($id)) {
            $this->_willRenderLayoutView = false;

        }
    }
    function pwgl($id, $subid) {
        if (!$this->_loadViewFromSubDirectory($id, $subid)) {
        }
    }
}