<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 12.07.14
 * Time: 18:53
 */

namespace Framework;

use Framework\Cache as Cache;
use Framework\Core\Exception as Exception;

class Cache extends Base {
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

    protected function _getExceptionForImplementation($method) {
        throw new Exception\Implementation("{$method} is not defined");
    }

    public function initialize() {
        Events::fire("framework.cache.initialize.before", array($this->type, $this->options));
        if (!$this->_type) {
            $configuration = Registry::get("configuration");
            if ($configuration) {
                $configuration = $configuration->initialize();
                // Diese Zeile lädt ./configuration/cache.ini von Disk.
                $parsed = $configuration->parse("configuration/cache");
                // Resultat: $parsed->cache->default // == cache.default.* in der ini
                if (!empty($parsed->cache->default) && !empty($parsed->cache->default->type)) {
                    $this->type = $parsed->cache->default->type;
                    unset($parsed->cache->default->type);
                    $this->options = (array)$parsed->cache->default;
                }
            }
        }
        if (!$this->_type) {
            throw new Exception\Argument("invalid cache type");
        }

        Events::fire("framework.cache.initialize.after", array($this->type, $this->options));
        switch ($this->_type) {
            case "memcached":
                return new Cache\Driver\Memcached($this->_options);
            case "database":
                return new Cache\Driver\Database($this->_options);
            case "session":
                return new Cache\Driver\Session($this->_options);
            default:
                throw new Exception\Argument("invalid type");
        }
    }
}
