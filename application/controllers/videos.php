<?php

use Homepage\Controller as Controller;
use Framework\ArrayMethods as ArrayMethods;
use Homepage\Video as Video;
use Homepage\Source as Source;
use Framework\RequestMethods as RequestMethods;

class Videos extends Controller {

    public function all() {
        $videos = Video::all();
        $this->actionView->set("videos", $videos);
    }

    public function play($id) {
        $view    = $this->getActionView();
        $video = Video::getWithSources($id);
        $view->set("video", $video);
    }


    public function play3d($id) {
        $view    = $this->getActionView();
        $video = Video::getWithSources($id);
        $view->set("video", $video);
    }

    /**
     * @before _secure, _admin
     */
    public function add() {
        $user   = $this->getUser();
        $view   = $this->getActionView();
        $videos = Video::all();
        $view->set("videos", $videos);
        if (RequestMethods::post("add")) {
            $title  = RequestMethods::post("title");
            $author = RequestMethods::post("author");
            $model  = new Video(array(
                "title"  => $title,
                "author" => $author
            ));
            if ($model->validate()) {
                $model->save();
                $view->set("success", true);
            }
        }
    }

    /**
     * @before _secure, _admin
     *
     * Better move to source
     */
    public function addsource($id) {
        if (RequestMethods::post("add")) {

        }
        if (RequestMethods::post("origin")) {
            self::redirect(RequestMethods::post("origin"));
        }
    }

    /**
     * @before _secure, _admin
     */
    public function edit($id) {
        $user = $this->getUser();
        $view = $this->getActionView();
        $video = Video::first(array(
            "id=?" => $id
        ));
        if (RequestMethods::post("edit")) {
            $title  = RequestMethods::post("title", $video->title);
            $author = RequestMethods::post("author", $video->author);
            $live   = RequestMethods::post("live", $video->live);
            $video->title  = $title;
            $video->author = $author;
            $video->live   = $live;
            if ($model->validate()) {
                $model->save();
                $view->set("success", true);
            }
        } else {
            $view->set("editable", $video);
        }
    }

    /**
     * @before _secure, _admin
     */
    public function delete($id) {
        $errors = array();
        $view   = $this->getActionView();
        $model  = Video::first(array(
            "id=?"   => $id,
            "live=?" => true
        ));
        if ($model) {
            $model->delete();
            $view->set("success", true);
            $errors = $model->errors;
        }
        $view->set("errors", $errors);
    }

    /**
     * @before _secure, admin
     */
    public function undelete($id) {
        $errors = array();
        $view   = $this->getActionView();
        $model  = Video::first(array(
            "id=?"   => $id,
            "live=?" => true
        ));
        if ($model) {
            $model->setLive(true);
            $model->setDeleted(false);
            if ($model->validate()) {
                $model->save();
            }
            $view->set("success", true);
            $errors = $model->errors;
        }
        $view->set("errors", $errors);
    }
} 