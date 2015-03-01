<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.07.14
 * Time: 23:06
 */

namespace Framework\Router\Route;

class View extends \Framework\Router\Route {
    /**
     * @readwrite
     */
    protected $_keys;

    public function matches($url) {
        return false;
    }
} 