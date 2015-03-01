<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.08.14
 * Time: 12:10
 */

namespace Framework\Encrypt;

use Framework\Base as Base;
use Framework\Encrypt\Exception as Exception;

class Driver extends Base {

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} not implemented");
    }

    public function initialize() {
        return $this;
    }

}