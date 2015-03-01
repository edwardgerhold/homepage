<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 18.07.14
 * Time: 21:06
 */

namespace Homepage;

class Model extends \Framework\Model {

    /**
     * @column
     * @readwrite
     * @primary
     * @type autonumber
     */
    protected $_id;
    /**
     * @column
     * @readwrite
     * @index
     * @type boolean
     */
    protected $_live;
    /**
     * @column
     * @readwrite
     * @index
     * @type boolean
     */
    protected $_deleted;
    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_created;
    /**
     * @column
     * @readwrite
     * @type datetime
     */
    protected $_modified;

    public function save() {
        $primary = $this->getPrimaryColumn();
        $raw = $primary["raw"];
        if (empty($this->$raw)) {
            $this->setCreated(date("Y-m-d H:i:s"));
            $this->setDeleted(false);
            $this->setLive(true);
        }
        $this->setModified(date("Y-m-d H:i:s"));
        parent::save();
    }


    static public function deleteRow($modelClass, $id) {
        $model = $modelClass::first(array(
            "id=?" => $id
        ));
        if ($model) {
            $model->setLive(false);
            $model->setDeleted(true);
            $model->save();
        }
    }

    static public function undeleteRow($modelClass, $id) {
        $model = $modelClass::first(array(
            "id=?" => $id
        ));
        if ($model) {
            $model->setLive(true);
            $model->setDeleted(false);
            $model->save();
        }
    }
}