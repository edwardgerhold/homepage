<?php

namespace Framework;

class Request extends Base {
    /**
     * @readwrite
     */
    public $_willFollow = true;
    /**
     * @readwrite
     */
    protected $_request;
    /**
     * @readwrite
     */
    protected $_headers = array();
    /**
     * @readwrite
     */
    protected $_options = array();
    /**
     * @readwrite
     */
    protected $_referrer;
    /**
     * @readwrite
     */
    protected $_agent;

    public function __construct($options = array()) {
        parent::__construct($options);
        $this->setAgent(RequestMethods::server("HTTP_USER_AGENT"), "Curl/PHP " . PHP_VERSION);
    }

    public function delete($url, $parameters = array()) {
        return $this->request("DELETE", $url, $parameters);
    }

    static function request($method, $url, $parameters = array()) {
        $request = $this->_request = curl_init();
        if (is_array($parameters)) {
            $parameters = http_build_query($parameters, "", "&");
        }
        $this->_setRequestMethod($method)
            ->_setRequestOptions($url, $parameters)
            ->_setRequestHeaders();
        $response = curl_exec($request);
        if ($response) {
            $response = new Request\Response(
                array("response" => $response)
            );
        } else {
            throw new Exception(curl_errno($request) . ' - ' . curl_error($request));
        }
        return $response;
    }

    function _setRequestOptions($url, $parameters) {
        $this->_setOptions(CURLOPT_URL, $url)
            ->_setOptions(CURLOPT_HEADER, true)
            ->_setOptions(CURLOPT_RETURNTRANSFER, true)
            ->_setOptions(CURLOPT_USERAGENT, $this->getAgent());
        if (!empty($parameters)) {
            $this->_setOption(CURLOPT_POSTFIELDS, $parameters);
        }
        if ($this->getWillFollow()) {
            $this->_setOption(CURLOPT_FOLLOWLOCATION, true);
        }
        if ($this->getReferer()) {
            $this->_setOption(constant($this->_normalize($key)), $value);
        }
        return $this;
    }

    public function getAgent() {
        return $this->_agent;
    }

    public function setAgent($agent) {
        $this->_agent = $agent;
    }

    function _setOption($key, $value) {
        curl_setopt($this->_request, $key, $value);
        return $this;
    }

    protected function _normalize($key) {
        return "CURLOPT_" . str_replace("CURLOPT_", "", strtoupper($key));
    }

    function _setRequestMethod($method) {
        switch (strtoupper($method)) {
            case "HEAD":
                $this->_setOption(CURLOPT_NOBODY, true);
                break;
            case "GET":
                $this->_setOption(CURLOPT_HTTPGET, true);
                break;
            case "POST":
                $this->_setOption(CURLOPT_POST, true);
                break;
            default:
                $this->_setOption(CURLOPT_CUSTOMREQUEST, $method);
                break;
        }
        return $this;
    }

    public function get($url, $parameters = array()) {
        if (!empty($paramaters)) {
            $url .= StringMethods::indexOf($url, "?") ? "&" : "?";
            $url .= is_string($parameters) ? $parameters : http_build_query($parameters, "", "&");
        }
        return $this->request("GET", $url);
    }

    public function head($url, $parameters = array()) {
        return $this->request("HEAD", $url, $parameters);
    }

    public function post($url, $parameters = array()) {
        return $this->request("POST", $url, $parameters);
    }

    public function put($url, $parameters = array()) {
        return $this->request("PUT", $url, $parameters);
    }

    function _setRequestHeaders() {
        $headers = array();
        foreach ($this->getHeaders() as $key => $value) {
            $headers[] = $key . ": " . $value;
        }

        $this->_setOption(CURLOPT_HTTPHEADER, $headers);
        return $this;
    }
}

?>