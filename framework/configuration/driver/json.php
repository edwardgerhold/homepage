<?php

namespace Framework\Configuration\Driver;

use Framework\Configuration as Configuration;
use Framework\Configuration\Exception as Exception;

class Json extends Configuration\Driver {

    public function parse($path) {
        if (empty($path)) {
            throw new Exception\Argument("\$path argument is not valid");
        }

        if (!isset($this->_parsed[$path])) {

            $config = new \stdClass();
            ob_start();
            include("{$path}.json");
            $raw = ob_get_contents();
            ob_end_clean();

            $assoc = false;
            if ($this->assoc) $assoc = true;

            try {
                $config = json_decode($raw, $assoc);
            } catch (\Exception $e) {
                throw new Exception($e);
            }

            $this->_parsed[$path] = $config;
        }
        return $this->_parsed[$path];
    }

    public function toJson($path) {
        if (isset($this->_parsed[$path])) {
            return json_encode($this->_parsed[$path]);
        }
        return "";
    }

} 