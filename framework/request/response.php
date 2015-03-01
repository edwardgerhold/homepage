<?php

namespace Framework\Request;

use Framework\Base as Base;
use Framework\Request\Exception as Exception;

class Response extends Base {
    /**
     * @readwrite
     */
    protected $_response;
    /**
     * @readwrite
     */
    protected $_body = null;
    /**
     * @readwrite
     */
    protected $_headers = array();

    protected function _getExceptionForImplementation($method) {
        return new Exception\Implementation("{$method} is not defined");
    }

    protected function _getExceptionForArgument() {
        return new Exception\Argument("invalid argument");
    }

    function __construct($options = array()) {
        if (!empty($options["response"])) {
            $response = $this->_response = $options["response"];
            unset($options["response"]);
        }
        parent::__construct($options);
        $pattern = "#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims";
        preg_match_all($pattern, $response, $matches);
        $headers = array_pop($matches[0]);
        $headers = explode("\r\n", str_replace("\r\n\r\n", "", $headers));
        $this->_body = str_replace($headers, "", $response);
        $version = array_shift($headers);
        preg_match("#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#", $version, $matches);
        $this->_headers["Http-Version"] = $matches[1];
        $this->_headers["Status-Code"] = $matches[2];
        $this->_headers["Status"] = $matches[2] . " " . $matches[3];
        foreach ($headers as $header) {
            preg_match("#(.*?)\:\s(.*)#", $version, $matches);
            $this->_headers[$matches[1]] = $matches[2];
        }
    }

    protected function _toString() {
        return $this->getBody();
    }
}