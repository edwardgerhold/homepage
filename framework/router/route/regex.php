<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.07.14
 * Time: 23:06
 */

namespace Framework\Router\Route;

class Regex extends Route {
    /**
     * @readwrite
     */
    protected $_keys;

    public function matches($url) {
        $pattern = $this->pattern;
        preg_match_all("#^{$pattern}$#", $url, $values);
        if (sizeof($values) && sizeof($values[0]) && sizeof($values[1])) {
            $derived = array_combine($this->keys, $values[1]);
            $this->parameters = array_merge($this->parameters, $derived);
            return true;
        }
        return false;
    }
} 