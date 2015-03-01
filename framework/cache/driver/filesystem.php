<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 30.07.14
 * Time: 17:23
 */

namespace Framework\Cache\Driver;

use Framework\Cache as Cache;
use Framework\Cache\Exception as Exception;

class Filesystem extends Cache\Driver {
    /**
     * @readwrite
     */
    protected $_directory = "/application/cache/filesystem";

    /**
     * @readwrite
     */
    protected $_isConnected = false;

    protected function _isValidService() {

        $isEmpty = empty($this->_service);

        $exists = file_exists($this->_directory);

        if ($this->isConnected && $exists && !isEmpty) {
            return true;
        }
        return false;
    }

    public function connect() {
        try {
            /*            $this->_service = new \Memcache();
                        $this->_service->connect(
                            $this->host,
                            $this->port
                        );*/
            $this->isConnected = true;
        } catch (\Exception $e) {
            throw new Exception\Service("unable to connect to service");
        }
        return $this;
    }

    public function disconnect() {

        if ($this->_isValidService()) {
            /*
                        $this->_service->close(); */
            $this->isConnected = false;
        }
        return $this;
    }

    public function get($key, $default = null) {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        // $value = $this->_service->get($key, MEMCACHE_COMPRESSED);

        // READ WRILW

        if ($value) {
            return $value;
        }
        return $default;
    }

    public function set($key, $value, $duration = 120) {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        // $this->_service->set($key, $value, MEMCACHE_COMPRESSED, $duration)

        // WRITE FILE
        return $this;
    }

    public function erase($key) {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        //     $this->_service->delete($key);

        // REMOVE FILE
        return $this;
    }
} 