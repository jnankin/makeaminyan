<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version5 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->dropTable('request');
        $this->dropTable('response');
    }

    public function down()
    {
        $this->createTable('request', array(
             'id' => 
             array(
              'type' => 'integer',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'event_type' => 
             array(
              'type' => 'enum',
              'values' => 
              array(
              0 => 'shachris',
              1 => 'mincha',
              2 => 'maariv',
              3 => 'tehilim',
              ),
              'notnull' => '1',
              'length' => '',
             ),
             'event_time' => 
             array(
              'type' => 'time',
              'notnull' => '',
              'length' => '25',
             ),
             'extra_reason' => 
             array(
              'type' => 'string',
              'notnull' => '',
              'length' => '255',
             ),
             'has_fired' => 
             array(
              'type' => 'boolean',
              'notnull' => '1',
              'default' => '0',
              'length' => '25',
             ),
             'minyan_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'initiating_user_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'created_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'updated_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             ), array(
             'type' => '',
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             'collate' => '',
             'charset' => '',
             ));
        $this->createTable('response', array(
             'id' => 
             array(
              'type' => 'integer',
              'primary' => '1',
              'autoincrement' => '1',
              'length' => '8',
             ),
             'request_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'user_id' => 
             array(
              'type' => 'integer',
              'notnull' => '1',
              'length' => '8',
             ),
             'status' => 
             array(
              'type' => 'enum',
              'values' => 
              array(
              0 => 'yes',
              1 => 'no',
              2 => 'maybe',
              ),
              'notnull' => '',
              'length' => '',
             ),
             'additional' => 
             array(
              'type' => 'integer',
              'notnull' => '',
              'length' => '4',
             ),
             'created_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             'updated_at' => 
             array(
              'notnull' => '1',
              'type' => 'timestamp',
              'length' => '25',
             ),
             ), array(
             'type' => '',
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             'collate' => '',
             'charset' => '',
             ));
    }
}