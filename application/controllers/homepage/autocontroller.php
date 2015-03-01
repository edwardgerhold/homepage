<?php
/**
 *
 * Edward´s Homepage is written by Edward Gerhold
 * http://github.com/edwardgerhold/homepage
 * Edward´s Homepage is originally developed for
 * http://linux-swt.de (c) 2014 Edward Gerhold
 * This is free and open source software for you.
 *
 * The Homepage Application Framework bases on the
 * "Pro PHP MVC" Framework from the namely equal book
 * by Chris Pitt released by http://apress.com.
 *
 * The application is Edward´s Homepage.
 * Load it into a PHPStorm evaluation copy from
 * http://jetbrains.com for the ultimate experience.
 *
 * Following more rules, the page is also developed with
 * the HTML5 Cookbook by T. Leadbetter and C. Hudson
 * and by Responsive Webdesign (german) by C. Zillgens
 *
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 05.09.14
 * Time: 20:01
 * Project: Edward´s Homepage
 */
namespace Homepage;

use Framework\RequestMethods as RequestMethods;

class AutoController extends Controller {
    /**
     * @read
     */
    protected function _all($modelClass) {
        $all = $modelClass::all();
        $this->getActionView()->set("all", $all);
    }

    protected function _add($modelClass) {
        if (RequestMethods::post("add")) {
            $model = new $modelClass(array());
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
            }
        }
    }

    protected function _edit($modelClass, $id) {
        if (RequestMethods::post("update")) {
            $model = $modelClass::first(array(
                "id=?" => $id
            ));
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
            }
        }
    }

    protected function _delete($modelClass, $id) {
        $model = $modelClass::first(array(
            "id=?" => $id
        ));
        if ($model) {
            $model->setLive(false);
            $model->setDeleted(true);
            if ($model->validate()) {
                $model->save();
                $this->getActionView()->set("success", true);
            }
        }
    }

    protected function _undelete($modelClass, $id) {
        $model = $modelClass::first(array(
            "id=?" => $id
        ));
        if ($model) {
            $model->setLive(true);
            $model->setDeleted(false);
            if ($model->validate()) {
                $model->save();
                $this->getActionView()->set("success", true);
            }
        }
    }
}

/*
class AutoImpl extends AutoController {
    public function all() {
        $this->_all(Project);
    }
    public function add() {
        $this->_add(Project);
    }
    public function edit($id) {
        $this->_edit(Project, $id);
    }
    public function delete($id) {
        $this->_delete(Project, $id);
    }
    public function undelete($id) {
        $this->_undelete(Project, $id);
    }
}
*/