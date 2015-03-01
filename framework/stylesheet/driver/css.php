<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 29.07.14
 * Time: 23:53
 */

namespace Framework\Stylesheet\Driver;

use Framework\Stylesheet\Style as Style;

class Css extends \Framework\Stylesheet\Driver {
    /**
     * @readwrite
     */
    protected $_styles;

    public function __construct($options = array()) {
        parent::__construct($options);
    }

    public function initialize() {
        $this->styles = array();
        return $this;
    }

    public function add($data) {
        if (is_array($data)) {
            $this->_styles[] = new Style($data);
        } else if ($data instanceof Style) {
            $this->_styles[] = $data;
        } else {
            throw new Exception("invalid argument to function add");
        }
        return $this;
    }

    public function get() {
        $styles = "";
        foreach ($this->styles as $script) {
            $styles .= $script->get() . "\n";
        }
        return $styles;
    }

} 