<?php

/**
 * MinyanUser filter form base class.
 *
 * @package    makeaminyan
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMinyanUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'minyan_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Minyan'), 'add_empty' => true)),
      'is_admin'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'use_email' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'use_phone' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'use_sms'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'user_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'minyan_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Minyan'), 'column' => 'id')),
      'is_admin'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'use_email' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'use_phone' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'use_sms'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('minyan_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MinyanUser';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'user_id'   => 'Number',
      'minyan_id' => 'ForeignKey',
      'is_admin'  => 'Boolean',
      'use_email' => 'Boolean',
      'use_phone' => 'Boolean',
      'use_sms'   => 'Boolean',
    );
  }
}
