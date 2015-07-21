<?php

/**
 * MinyanUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    makeaminyan
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class MinyanUser extends BaseMinyanUser
{
    public function canAccess($guardUser) {
        if ($guardUser == null) return false;

        return $this->getUserId() == $guardUser->getId() || $this->getMinyan()->isAdmin($guardUser->getId());
    }

    public function isAdmin(){
        return $this->getIsAdmin();
    }

    public function getUser(){
        return Doctrine::getTable('SfGuardUser')->find($this->getUserId());
    }
}