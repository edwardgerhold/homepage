<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 21:30
 */

namespace Framework\Cache;

use Framework\Base as Base;
use Framework\Cache\Exception as Exception;

class Driver extends Base {

    protected function _getExceptionForImplementation($method) {
        throw new Exception("{$method} not implemented");
    }

    public function initialize() {
        return $this;
    }

}