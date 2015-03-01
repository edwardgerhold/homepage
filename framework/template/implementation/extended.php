<?php

namespace Framework\Template\Implementation;

use Framework\Registry as Registry;
use Framework\Request as Request;
use Framework\RequestMethods as RequestMethods;
use Framework\StringMethods as StringMethods;
use Framework\Template as Template;
use Framework\Localize as Localize;
use Framework\Markup as Markup;

class Extended extends Standard {
    /**
     * @readwrite
     */
    protected $_defaultPath = "application/views";
    /**
     * @readwrite
     */
    protected $_defaultKey = "_data";
    /**
     * @readwrite
     */
    protected $_index = 0;

    public function __construct($options = array()) {

        parent::__construct($options);

        $this->_map = array(

                "partial" => array(
                    "opener" => "{partial",
                    "closer" => "}",
                    "handler" => "_partial"),
                "include" => array(
                    "opener" => "{include",
                    "closer" => "}",
                    "handler" => "_include"),
                "yield" => array(
                    "opener" => "{yield",
                    "closer" => "}",
                    "handler" => "yields"),

                // eddie
                // {inline filename}
                "inline" => array(
                    "opener" => "{inline",
                    "closer" => "}",
                    "handler" => "_inline"
                ),

                // {local STRING IN Localize::TEXTS}
                "local" => array(
                    "opener" => "{local",
                    "closer" => "}",
                    "handler" => "_local"
                ),

                // {editor} loads what \editor gives free
                "editor" => array(
                    "opener" => "{editor",
                    "closer" => "}",
                    "handler" => "_editor"
                ),

                // {error $errors, "name"}
                "error" => array(
                    "opener" => "{error",
                    "closer" => "}",
                    "handler" => "_error"
                )

            ) + $this->_map;

        $this->_map["statement"]["tags"] = array(
                "set" => array(
                    "isolated" => false,
                    "arguments" => "{key}",
                    "handler" => "set"
                ),
                "append" => array(
                    "isolated" => false,
                    "arguments" => "{key}",
                    "handler" => "append"
                ),
                "prepend" => array(
                    "isolated" => false,
                    "arguments" => "{key}",
                    "handler" => "prepend"
                )
            ) + $this->_map["statement"]["tags"];
    }

    protected function _include($tree, $content) {
        $template = new Template(array(
            "implementation" => new self()
        ));
        $file = trim($tree["raw"]);
        $path = $this->getDefaultPath();
        $content = file_get_contents(APP_PATH . "/{$path}/{$file}");
        $template->parse($content);
        $index = $this->_index++;
        return "function anon_{$index}(\$_data) {\n" . $template->getCode() . "\n};\$_text[] = anon_{$index}(\$_data);";
    }

    protected function _partial($tree, $content) {
        $address = trim($tree["raw"], " /");
        if (StringMethods::indexOf($address, "http") != 0) {
            $host = RequestMethods::server("HTTP_HOST");
            $address = "http://{$host}/{$address}";
        }
        $request = new Request();
        $response = addslashes(trim($request->get($address)));
        return "\$_text[] = \"{$response}\";";
    }

    protected function _getKey($tree) {
        if (empty($tree["arguments"]["key"])) return null;
        return trim($tree["arguments"]["key"]);

    }

    protected function _setValue($key, $value) {
        if (!empty($key)) {
            $data = Registry::get($this->defaultKey, array());
            $data[$key] = $value;
            Registry::set($this->defaultKey, $data);
        }
    }

    protected function _getValue($key) {
        $data = Registry::get($this->defaultKey);
        if (isset($data[$key])) {
            return $data[$key];
        }
        return "";
    }

    public function set($key, $value) {
        if (StringMethods::indexOf($value, "\$_text") > -1) {
            $first = StringMethods::indexOf($value, "\"");
            $last = StringMethods::lastIndexOf($value, "\"");
            $value = stripslashes(substr($value, $first + 1, ($last - $first)));
        }
        if (is_array($key)) {
            $key = $this->_getKey($key);
        }
        $this->_setValue($key, $value);
    }

    public function append($key, $value) {
        if (is_array($key)) {
            $key = $this->_getKey($key);
        }
        $previous = $this->_getValue($key);
        $this->set($key, $previous . $value);
    }

    public function prepend($key, $value) {
        if (is_array($key)) {
            $key = $this->_getKey($key);
        }
        $previous = $this->_getValue($key);
        $this->set($key, $value . $previous);
    }

    public function yields($tree, $content) {
        $key = trim($tree["raw"]);
        $value = addslashes($this->_getValue($key));
        return "\$_text[] = stripslashes(stripslashes(\"{$value}\"));";
    }

    /*
     * like the include handler, my {inline file} handler
     * allows to inline any content
     */


    protected function _inline($tree, $content) {
        $key = trim($tree["raw"]);
        $data = addslashes(file_get_contents(APP_PATH . "/{$key}"));
        return "\$_text[] = stripslashes(\"{$data}\");";
    }

    protected function _local($tree, $content) {
        $raw = trim($tree["raw"]);
        $local = Localize::get("{$raw}");
        return "\$_text[] = \"{$local}\"";
    }

    protected function _error($tree, $content) {
        $raw = trim($tree["raw"]);
        $split = explode(",", $raw);
        $arr = trim($split[0]);
        $key = trim($split[1]);
        $error = Markup::errors($arr, $key);
        return "\$_text[] = \"{$error}\"";
    }

    protected function _editor($tree, $content) {
        $editor = Registry::get("editor");
        $raw = trim($tree["raw"]);
        if (isset($editor)) {
            $editor = $editor->get("{$raw}"); // {editor   all this is raw } => editor->get("all this is raw");
        } else $editor = "<p><b>Error:</b> Error loading Editor!</p>";
        return "\$_text[] = \"{$editor}\";";
    }

}