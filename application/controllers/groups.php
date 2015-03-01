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
 * Created by PhpStorm
 * Date: 28.08.14
 * Time: 19:45
 * User: Edward Gerhold
 * Project Edward´s Homepage
 */
use Homepage\UserGroup as UserGroup;
use Homepage\GroupRelation as GroupRelation;

class Groups extends \Homepage\Controller {
    /**
     * @before _secure
     *
     * @description Joining a group
     */
    function join($id) {
        $user  = $this->getUser();
        $group = UserGroup::first(array(
            "id=?" => $id
        ));
        if ($user && $group) {
            $rel = new GroupRelation(array(
                "user"      => $user->id,
                "usergroup" => $group->id
            ));
            if ($rel->validate()) {
                $rel->save();
                $this->_actionView->set("success", true);
            }
        }
    }

    /**
     * @before _secure
     *
     * @description Leaving a group
     */
    function leave($id) {
        $user  = $this->getUser();
        $group = UserGroup::first(array(
            "id=?" => $id
        ));
        if ($user && $group) {
            $rel = GroupRelation::first(array(
                "id=?"    => $user->id,
                "group=?" => $group->id
            ));
            if ($rel) {
                $rel->setLive(false);
                $rel->setDeleted(true);
                if ($rel->validate()) {
                    $rel->save();
                    $this->_actionView->set("success", true);
                }
            }
        }
    }
} 