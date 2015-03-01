u<?php
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
* Date: 22.08.14
* Time: 15:15
* User: Edward Gerhold
* Project Edward´s Homepage
*/

use \Framework\Test as Tester;
use \Framework\Test\Model\TestSet as TestSet;
use \Framework\Test\Model\TestCase as TestCase;

class Tests extends \Homepage\Controller {

    /**
     * @readwrite
     */
    protected $_test;

    /**
     * @readwrite
     */
    protected $_before;
    /**
     * @readwrite
     */
    protected $_after;

    public function __construct($options = array()) {
        parent::__construct($options);
        if (empty($this->_test)) {
            $this->_test = new Tester();
        }
    }
    /*
    public static function add($callback, $title = "Unnamed Test", $set = "General") {
        public static function run($before = null, $after = null) {

        }
    */

    public function run() {

    }

    public function all() {
        $results = $this->_test->run($this->_before, $this->_after);
        $view = $this->getActionView();
        $view->set("results", $results);
    }

    public function add() {
        if (RequestMethods::post("hooks")) {
            $before = RequestMethods::post("before");
            $after = RequestMethods::post("after");

        }
        if (RequestMethods::post("add")) {
            $code = RequestMethods::post("callback");
            $callback = create_function("", $code);
            $title = RequestMethods::post("title");
            $set = RequestMethods::post("set");
            $this->_test->add($callback, $title, $set);
        }
    }
} 