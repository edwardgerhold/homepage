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
* Date: 28.08.14
* Time: 11:29
* User: Edward Gerhold
* Project Edward´s Homepage
*/

use \Homepage\Controller as Controller;

class Html5 extends Controller {

    function websocket($id=null) {
    }
    function history($id=null) {
    }
    function notepad($id=null) {
    }
    function messaging($id=null){
    }
    function canvas($id=null) {
    }
    function polygon($id=null) {
    }
    function rubberband($id=null) {
    }
    
    function experiments($id=null) {
	if ($this->_loadViewFromSubDirectory($id)) {
	
	}
    }

    /**
     * @protected
     */
     /*
    public function render() {
        $this->layoutView->set("actions", $this->_getPublicActions());
        parent::render();
    }
    */

}