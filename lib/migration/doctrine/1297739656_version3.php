<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version3 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('request', 'event_time', 'time', '25', array(
             'notnull' => '',
             ));
    }

    public function down()
    {

    }
}