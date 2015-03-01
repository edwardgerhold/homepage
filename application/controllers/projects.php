<?php

use Framework\RequestMethods as RequestMethods;
use Homepage\Project as Project;

class Projects extends Homepage\Controller {
    /**
     * @read
     */
    protected $_model = "\\Homepage\\Project";

    public function all() {

    }
    public function add() {

        if (RequestMethods::post("add")) {

            // Hier teste ich das automatische auslesen von
            // Form Feldern die Properties entsprechen um
            // einen generischen Controller mit add-edit-delete-undelete-all-entry
            // zu haben, der für kein Dokument, solange es mit dem Model übereinstimmt
            // mehr programmiert werden muss.

            $model = new $this->_model(array());
            foreach ($model->inspector->getClassProperties() as $property) {
                $meta = $model->inspector->getPropertyMeta($property);
                if (isset($meta["@column"]) && $meta["@column"] == 1) {
                        $data = RequestMethods::post($property);
                        if (isset($data)) {
                            $model->$property = $data;
                        }
                }
            }
            if ($model->validate()) {
                $model->save();
                $this->getActionView()->set("success", true);
            } else {
                // $this->getActionView()->set("errors", $model->getErrors());
            }
        }
    }
    public function edit() {

    }
    public function delete($id) {

    }
    public function undelete($id) {

    }
}