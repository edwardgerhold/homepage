<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 25.07.14
 * Time: 09:58
 */

namespace Framework\Cache\Driver;

use Framework\Cache as Cache;
use Framework\Cache\Exception as Exception;

class Database extends Cache\Driver {
    /**
     * @readwrite
     */
    protected $_table = "framework_cache";
    /**
     * @readwrite
     */
    protected $_isConnected = false;

    protected function _isValidService() {

        $isEmpty = empty($this->_service);
        $isInstance = $this->_service instanceof \Framework\Database\Connector\MySQL;
        if ($this->isConnected && $isInstance && !isEmpty) {
            return true;
        }
        return false;
    }

    public function connect() {
        try {
            $this->_service = Registry::get("database");
            $this->_service->connect();
            $this->isConnected = true;
        } catch (\Exception $e) {
            throw new Exception\Service("unable to connect to service");
        }
        return $this;
    }

    public function disconnect() {
        if ($this->_isValidService()) {
            $this->_service->close();
            $this->isConnected = false;
        }
        return $this;
    }

    public function get($key, $default = null) {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }

        $fields = $this->_service
            ->query()
            ->from($this->table, array("*"))
            ->where("key=?", $key)
            ->first();

        $value = $fields["value"];
        if ($value) {
            return $value;
        }
        /**
         * duration testen weil key sonst invalid ist
         */

        return $default;
    }

    public function set($key, $value, $duration = 120) {

        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }

        $data = $this->_service
            ->query()
            ->from($this->table, array("*"))
            ->where("key=?", $key)
            ->first();

        if ($data) {

            $data->created = date("Y-m-d H:i:s");
            $data->expires = date("Y-m-d H:i:s", time() + $duration * 1000);

            if ($data->validate()) {
                $data->save();
            }
        } else {

            $data = new \Framework\Cache\Model\Cache(array(
                "key" => $key,
                "value" => $value,
                "created" => date("Y-m-d H:i:s", time()),
                "expires" => date("Y-m-d H:i:s", time() + $duration * 1000)
            ));
        }

        return $this;
    }

    public function erase($key) {
        if (!$this->_isValidService()) {
            throw new Exception\Service("Not connected to a valid service");
        }
        $data = $this->_service
            ->query()
            ->from($this->table, array("*"))
            ->where("key=?", $key)
            ->first();

        if ($data) {
            $data->delete();
        }

        return $this;
    }
}
