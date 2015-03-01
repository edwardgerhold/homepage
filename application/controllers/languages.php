<?php
/**
 * Created by PhpStorm.
 * User: Edward Gerhold
 * Date: 19.08.14
 * Time: 06:48
 */

class Languages extends \Homepage\Controller {

    /**
     *
     */
    public function select($lang = null) {

        $languages = Localize::getAvailableLanguages();

        if (RequestMethods::post("select")) {
            $lang = RequestMethods::post("lang");
        } else if ($lang != null) {
            $view->set("language", $lang);
        }
        if (Localize::setLanguage($lang)) {
            $view->set("success", true);
        }

        $view->set("languages", $languages);

    }
} 