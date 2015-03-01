<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 20:25
 */

namespace Framework\Javascript;

use Framework\Base as Base;
use Framework\Javascript\Exception as Exception;

class Driver extends Base {

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} not implemented");
    }

    public function initialize() {
        return $this;
    }

}