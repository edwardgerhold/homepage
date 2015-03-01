<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 20:25
 */

namespace Framework\Configuration;

use Framework\Base as Base;
use Framework\Core\Exception as Exception;

class Driver extends Base {

    /**
     * @readwrite
     *
     * Return associative array
     */
    protected $_assoc;

    /**
     * @readwrite
     *
     * The result of a parse
     */
    protected $_parsed = array();

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} not implemented");
    }

    public function initialize() {
        return $this;
    }

}