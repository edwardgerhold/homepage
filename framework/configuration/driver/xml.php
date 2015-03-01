<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 28.07.14
 * Time: 21:41
 */

namespace Framework\Configuration\Driver;

use Framework\Configuration as Configuration;

class Xml extends Configuration\Driver {

    public function parse($path) {
        if (empty($path)) {
            throw new Exception\Argument("\$path argument is not valid");
        }
        if (!isset($this->_parsed[$path])) {
            /**
             * TODO: Xml Parser
             */
            $this->_parsed[$path] = new stdClass();
        }
        // $parsed->* aus dem Buch ist eine stdClass()
        return $this->_parsed[$path];
    }

} 