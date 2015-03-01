<?php

namespace Framework\Cookie;

use Framework\Base as Base;
use Framework\Cookie\Exception as Exception;

class Driver extends Base {
    public function initialize() {
        return $this;
    }

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} method not implemented");
    }
}

?>