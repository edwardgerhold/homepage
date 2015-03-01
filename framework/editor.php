<?php
namespace Framework;

use Framework\Editor\Exception as Exception;

class Editor extends Base {

    /**
     * @readwrite
     */
    protected $_type;

    /**
     * @readwrite
     */
    protected $_options;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        Events::fire("framework.editor.initialize.before", array($this->type, $this->options));
        if (!$this->type) {
            $configuration = Registry::get("configuration");
            if ($configuration) {
                $configuration = $configuration->initialize();
                $parsed = $configuration->parse("configuration/editor");
                if (!empty($parsed->editor->default->type)) {
                    // INI: editor.default.type =
                    // JSON: { "editor": { "default": { "type": "" } } }
                    $this->type = $parsed->editor->default->type;
                    $this->options = (array)$parsed->editor->default;
                }
            }
            throw new Exception\Implementation("invalid editor driver type");
        }
        Events::fire("framework.editor.initialize.after", array($this->type, $this->options));
        switch ($this->type) {
            case "server":
                return new Editor\Driver\Server($this->options);
            case "tinymce":
                return new Editor\Driver\TinyMCE($this->options);
            default:
                throw new Exception\Implementation("invalid editor driver type");
        }
    }
}

