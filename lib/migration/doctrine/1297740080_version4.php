<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version4 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('request', 'has_fired', 'boolean', '25', array(
             'notnull' => '1',
             'default' => '0',
             ));
    }

    public function down()
    {
        $this->removeColumn('request', 'has_fired');
    }
}