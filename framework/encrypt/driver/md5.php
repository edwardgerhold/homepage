<?php

/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 25.07.14
 * Time: 23:59
 */

namespace Framework\Encrypt\Driver;

use Framework\Encrypt as Encrypt;

class MD5 extends Encrypt\Driver {
    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        return $this;
    }

    public function encrypt($data) {
        return md5($data);
    }

    public function decrypt($data) {
        return md5($data, true);
    }
}