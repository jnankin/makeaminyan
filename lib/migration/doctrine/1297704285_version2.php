<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version2 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('response', 'created_at', 'timestamp', '25', array(
             'notnull' => '1',
             ));
        $this->addColumn('response', 'updated_at', 'timestamp', '25', array(
             'notnull' => '1',
             ));
    }

    public function down()
    {
        $this->removeColumn('response', 'created_at');
        $this->removeColumn('response', 'updated_at');
    }
}