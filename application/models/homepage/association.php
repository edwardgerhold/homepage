<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.08.14
 * Time: 09:14
 */

namespace Homepage;

/**
 * Class Association
 * @package Homepage
 *
 *
 */
class Association extends \Homepage\Model {
    /**
     * @readwrite
     */
    protected $_table = "homepage_association";

    /*
     * Generische Links
     */

    /**
     * @column
     * @type integer
     * @label link key 1
     */
    protected $_key1;
    /**
     * @column
     * @type integer
     * @label value für key1
     */
    protected $_value1;
    /**
     * @column
     * @type text
     * @label link key 2
     */
    protected $_key2;
    /**
     * @column
     * @type integer
     * @label value für key2
     */
    protected $_value2;

    protected static function fetch($key1, $key2 = null, $value1 = null, $value2 = null) {
        $fields = array(
            "key1=?" => "{$key1}"
        );
        if ($key2 != null) {
            $fields += array("key2=?" => "{$key2}");
        }
        if ($value1 != null) {
            $fields += array("value1"=>"{$value1}");
        }
        if ($value2 != null) {
            $fields += array("value1"=>"{$value2}");
        }
        return self::all($fields);
    }

}