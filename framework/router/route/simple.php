<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.07.14
 * Time: 23:06
 */

namespace Framework\Router\Route;

class Simple extends \Framework\Router\Route {
    /**
     * @readwrite
     */
    protected $_keys;

    public function matches($url) {
        $pattern = $this->pattern;
        $keys = $this->keys;
        $values = array();

        preg_match_all("#:([a-zA-Z0-9]+)#", $pattern, $keys);
        if (sizeof($keys) && sizeof($keys[0]) && sizeof($keys[1])) {
            $keys = $keys[1];   // holt :id und :name
            $this->keys = $keys;
        } else {
            return preg_match("#^{$pattern}$#", $url);
        }

        $pattern = preg_replace("#(:[a-zA-Z0-9]+)#", "([a-zA-Z0-9-_]+)", $pattern);
        preg_match_all("#^{pattern}$#", $url, $values);
        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])) {
            $derived = array_combine($this->keys, $values[1]);
            $this->parameters = array_merge($this->parameters, $derived);
            return true;
        }
        return false;

    }
} 