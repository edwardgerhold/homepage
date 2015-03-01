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
 * Time: 21:56
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */
namespace Framework;

use \Iterator as PHPIteratorInterface;

class Iterator extends Base implements PHPIteratorInterface {

    protected $_iterable;
    protected $_count = 0;
    protected $_index = 0;
    protected $_keys = array();
    protected $_type = -1;

    public function __construct($value = array()) {
        parent::__construct();
        $this->_inspect($value);
        $this->_iterable = $value;
        $this->rewind();
    }

    protected function _inspect($value) {
        if (is_array($value)) {
            $this->_type = 1;
        } else if (is_object($value)) {
            $this->_type = 0;
        } else {
            throw new Exception("not an iterable");
        }
    }

    public function next() {
        if ($this->valid()) {
            $this->_index++;
        }
    }

    public function key() {
        if ($this->valid()) {
            return $this->_keys[$this->_index];
        }
    }

    public function valid() {
        return (boolean)($this->_count > $this->_index);
    }

    public function current() {
        if ($this->valid()) {
            $k = $this->_keys[$this->_index];
            switch ($this->_type) {
                case 0:
                    return $this->_iterable->$k;
                case 1:
                    return $this->_iterable[$k];
            }
        }
        return null;
    }

    public function rewind() {
        $this->_index = 0;
        $this->count(true);
    }

    public function at() {
        return $this->_index;
    }

    public function count($recount = false) {
        if (!$this->_count || $recount) {
            switch ($this->_type) {
                case 0:
                    $this->_count = sizeof($this->_keys = get_object_vars($this->_iterable));
                    break;
                case 1:
                    $this->_count = sizeof($this->_keys = array_keys($this->_iterable));
                    break;
            }
        }
        return $this->_count;
    }
}