<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 20:25
 */

namespace Framework\Stylesheet;

use Framework\Base as Base;
use Framework\Stylesheet\Exception as Exception;

class Driver extends Base {

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} not implemented");
    }

    public function initialize() {
        return $this;
    }

}