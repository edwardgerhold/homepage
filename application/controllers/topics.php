<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 10.08.14
 * Time: 16:06
 */

use \Homepage\Topic as Topic;
use \Framework\RequestMethods as RequestMethods;

class Topics extends \Homepage\Controller {

    function __construct($options = array()) {
        parent::__construct($options);
    }

    /**
     * @before _secure, _admin
     */
    public function add() {

        $view = $this->getActionView();
        $user = $this->getUser();

        if (RequestMethods::post("add") && $user->admin) {

            $name = RequestMethods::post("name");
            $parent = RequestMethods::post("parent", null);

            $model = new Topic(array(
                "name=?" => $name,
                "parent=?" => $parent,
                "live=?"=>true,
                "deleted=?"=>false
            ));

            if ($model->validate()) {
                $model->save();
            }
        
        }
        $all = Topic::all(array(
            "live=?" => true,
            "deleted=?" => false
        ), array("*"));

        $view->set("topics", $all);
    }

    /**
     * @before _secure, _admin
     */
    public function edit($id) {
        $view = $this->getActionView();

        $cat = Topic::first(array(
            "id=?" => $id
        ), array("*"), "desc");


        // Falls speichern, dann mache es
        if (RequestMethods::post("save")) {

            $name = RequestMethods::post("name");
            //$parent = RequestMethods::post("parent", null);

            $cat->name = $name;

            //$cat->setParent($parent);

            if ($cat->validate()) {
                $cat->save();
                $view->set("success", true);
            }

            $errors = $cat->errors;
            $view->set("errors", $errors);

        } else {
            // Holen und zum Editieren setzen
            $view->set("editable", $cat);
        }

    }

    /**
     * @before _secure, _admin
     */
    public function delete($id) {
        $errors = array();

        $view = $this->getActionView();

        $model = Topic::first(array(
            "id=?" => $id
        ));

        if ($model) {

            $model->delete();
            $view->set("success", true);

            $this->_successes += array(""=>"");
            $errors = $model->errors;

        }

        $view->set("errors", $errors);
    }
}