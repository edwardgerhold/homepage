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
* Date: 03.09.14
* Time: 11:20
* Project: Edward´s Homepage
*/

use Framework\RequestMethods as RequestMethods;
use Homepage\Source as Source;

class Sources extends Homepage\Controller {

    /**
     * @before _secure, _admin
     */
    public function addaudio($id) {
        $this->_add("audio", $id);
    }
    /**
     * @before _secure, _admin
     */
    public function addvideo($id) {
        $this->_add("video", $id);
    }
    /**
     * @before _secure, _admin
     */
    public function add($name, $id) {
        $this->_add($name, $id);
    }

    protected function _add($type, $id) {
        if (!isset($type, $id) || ($type != "video" && $type != "audio")) {
            throw new Framework\Controller\Exception\Action("that doesnt work");
        }
        $view = $this->getActionView();
        if (RequestMethods::post("add")) {
            $media = RequestMethods::post("media");
            $type = RequestMethods::post("type");
            $src = RequestMethods::post("src");
            $data = array(
                "media" => $media,
                "type" => $type,
                "src" => $src,
                $type => $id
            );
            $source = new Source($data);
            if ($source->validate()) {
                $source->save();
                $view->set("success", true);
            }
            if (RequestMethods::post("origin")) {
                self::redirect(RequestMethods::post("origin"));
            }
        }
    }
}