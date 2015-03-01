<?php

namespace Framework\Configuration\Driver;

use Framework\ArrayMethods as ArrayMethods;
use Framework\Configuration\Exception as Exception;
use Framework\Configuration as Configuration;

class Database extends Configuration\Driver {

    public function parse($path) {
        if (empty($path)) {
            throw new Exception\Argument("\$path argument is not valid");
        }
        if (!isset($this->_parsed[$path])) {
            $config = array();

            $pairs = Configuration\Model\Configuration::all(array(
                "path=?" => $path
            ));

            /** added associative arrays
             *  the book returns stdClass objects
                but Localize needs associative arrays
                that´s how we like it*/
            if ($this->assoc) {
                return $this->_parsed[$path] = $pairs;
            }
            if ($pairs == false) {
                throw new Exception("could not parse results from database query");
            }
            foreach ($pairs as $key => $value) {
                $config = $this->_pair($config, $key, $value);
            }
            $this->_parsed[$path] = ArrayMethods::toObject($config);
        }
        return $this->_parsed[$path];
    }

    protected function _pair($config, $key, $value) {
        if (strstr($key, ".")) {
            $parts = explode(".", $key, 2);
            if (empty($config[$parts[0]])) {
                $config[$parts[0]] = array();
            }
            $config[$parts[0]] = $this->_pair($config[$parts[0]], $parts[1], $value);
        } else {
            $config[$key] = $value;
        }
        return $config;
    }
} 