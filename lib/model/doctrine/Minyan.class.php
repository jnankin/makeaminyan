<?php

/**
 * Minyan
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    makeaminyan
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Minyan extends BaseMinyan {

    public function canAccess($guardUser) {
        if ($guardUser == null) return false;

        return $this->isAdmin($guardUser->getId());
    }

    public function isAdmin($userId) {
        foreach ($this->getUsers() as $user) {
            if ($user->getUserId() == $userId) {
                return $user->isAdmin();
            }
        }
        return false;
    }

    public function hasSubscriber($userId) {
        foreach ($this->getUsers() as $user) {
            if ($user->getUserId() == $userId) {
                return true;
            }
        }
        return false;
    }

}
