<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version7 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('blast', 'blast_minyan_id_minyan_id', array(
             'name' => 'blast_minyan_id_minyan_id',
             'local' => 'minyan_id',
             'foreign' => 'id',
             'foreignTable' => 'minyan',
             ));
        $this->createForeignKey('blast_response', 'blast_response_blast_id_blast_id', array(
             'name' => 'blast_response_blast_id_blast_id',
             'local' => 'blast_id',
             'foreign' => 'id',
             'foreignTable' => 'blast',
             ));
        $this->addIndex('blast', 'blast_minyan_id', array(
             'fields' => 
             array(
              0 => 'minyan_id',
             ),
             ));
        $this->addIndex('blast_response', 'blast_response_blast_id', array(
             'fields' => 
             array(
              0 => 'blast_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('blast', 'blast_minyan_id_minyan_id');
        $this->dropForeignKey('blast_response', 'blast_response_blast_id_blast_id');
        $this->removeIndex('blast', 'blast_minyan_id', array(
             'fields' => 
             array(
              0 => 'minyan_id',
             ),
             ));
        $this->removeIndex('blast_response', 'blast_response_blast_id', array(
             'fields' => 
             array(
              0 => 'blast_id',
             ),
             ));
    }
}