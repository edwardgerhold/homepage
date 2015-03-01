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
 * Created by PhpStorm
 * Date: 29.08.14
 * Time: 16:34
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */

namespace Framework;

use Framework\Spamfilter\Model\SecurityIssue as SecurityIssue;

class SpamFilter extends Base {
    /**
     * @readwrite
     */
    protected $_maxIssuesPerUser = 5;

    /**
     * @readwrite
     */
    protected $_allow = array(
        "porn" => false,
        "ads" => false,
        "piracy" => false,
        "offending" => false
    );

    /**
     * @readwrite
     */
    protected $_score;

    /**
     *
     * rules for punishment
     * add a security issue in the database
     * mail the user from the system that he has 9 of 10 left until he is called marketing spammer and deleted
     */

    protected function _checkUser($user) {
        $count = SecurityIssue::count(array(
            "user=?" => $user,
            "live=?" => true,
            "deleted=?" => false
        ));
        if ($count >= $this->_maxIssuesPerUser) {
            /*
             * Sperre Account
             */

            /**
             *
             * Hier breche ich die Grenzen zwischen Framework\Spamfilter
             *
             * und Homepage\User
             *
             * was bedeutet, dass ich mir das nochmal überlegen sollte,
             *
             *
             * die checkUser Funktion anderswohin zu bringen,
             * create issue geht noch hier, weil das Model mit der
             * user id als key recht unabhängig bleibt und gewöhnlich ist,
             * dass man es wiederverwenden kann, sogar anderswo ohne probleme
             * weils halt nur einen int key für den user gibt, mehr nicht.
             */

        }
    }

    protected function _createIssue($user, $reason, $data) {
        $issue = new SecurityIssue(array(
            "user" => $user,
            "reason" => $reason,
            "data" => $data
        ));
        if ($issue->validate()) {
            $issue->save();
        }
    }

    public function validate($text) {
        $type = $this->_validate($text);
        if (!self::_isAllowed($type)) {
            // Achtung spam
            return false;
        }

    }

    protected function _validate($text) {
        return true;
    }

    protected function _isAllowed($type) {
        if (is_array($type)) {
            foreach ($type as $i => $_type) {
                foreach ($this->_allow as $key => $value) {
                    if (($key == $_type) && $value == false) return false;
                }
            }
            return true;
        } else {
            foreach ($this->_allow as $key => $value) {
                if ($key == $type) return (boolean)$value;
            }
        }
        return true;
    }
}