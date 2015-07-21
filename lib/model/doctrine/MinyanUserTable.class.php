<?php

/**
 * MinyanUserTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MinyanUserTable extends Doctrine_Table {

    /**
     * Returns an instance of this class.
     *
     * @return object MinyanUserTable
     */
    public static function getInstance() {
        return Doctrine_Core::getTable('MinyanUser');
    }

    public function getMinyanUser($minyanId, $userId) {
        return $this->createQuery()->where('minyan_id = ?', $minyanId)->andWhere('user_id = ?', $userId)->fetchOne();
    }

}