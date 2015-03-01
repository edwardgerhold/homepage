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
 * Time: 11:28
 * Project: Edward´s Homepage
 */

namespace Requirements;

use Framework\RequestMethods as RequestMethods;
use Homepage\Crc as Crc;

class CrcCards extends Homepage\Controller {

    public function help() {
        // help using crc cards
    }

    public function all() {
        $cards = Crc::all();
        $this->getActionView()->set("all", $cards);
    }

    public function add() {
        $view = $this->getActionView();
        if (RequestMethods::post("add")) {
            $class          = RequestMethods::post("class");
            $responsibility = RequestMethods::post("responsibility");
            $collaborations = RequestMethods::post("collaborations");
            $model = new Crc(array(
                "class"          => $class,
                "responsibility" => $responsibility,
                "collaborations" => $collaborations
            ));
            if ($model->validate()) {
                $model->save();
                $view->set("success", true);
                $view->set("card", $model);
            } else {
                $view->set("errors", true);
            }
        }
    }

    public function edit($id) {
        $class          = null;
        $responsibility = null;
        $collaborations = null;
        $model = Crc::first(array(
            "id=?"      => $id,
            "live=?"    => true,
            "deleted=?" => false
        ));
        $view = $this->getActionView();
        if (RequestMethods::post("update")) {
            if ($model) {
                $class          = $model->class;
                $responsibility = $model->responsibility;
                $collaborations = $model->collaborations;
                $class          = RequestMethods::post("class", $class);
                $responsibility = RequestMethods::post("responsibility", $responsibility);
                $collaborations = RequestMethods::post("collaborations", $collaborations);
                $model->class          = $class;
                $model->responsibility = $responsibility;
                $model->collaborations = $collaborations;
                if ($model->validate()) {
                    $model->save();
                    $view->set("success", true);
                    $view->set("editable", $model);
                    $view->set("card", $model);
                } else {
                    $view->set("errors", true);
                }
            } else {
                $view->set("errors", true);
            }
        } else {
            if ($model) {
                $view->set("editable", $model);
            } else {
                $view->set("errors", true);
            }
        }
    }

    public function delete($id) {
        $model = Crc::first(array(
            "id=?" => $id
        ));
        if ($model) {
            $model->setLive(false);
            $model->setDeleted(true);
            $model->save();
        }
    }

    public function undelete($id) {
        $model = Crc::first(array(
            "id=?" => $id
        ));
        if ($model) {
            $model->setLive(true);
            $model->setDeleted(false);
            $model->save();
        }
    }
}