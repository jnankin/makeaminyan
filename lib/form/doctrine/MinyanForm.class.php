<?php

/**
 * Minyan form.
 *
 * @package    makeaminyan
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MinyanForm extends BaseMinyanForm {

    public function configure() {
        unset($this['id']);
        unset($this['identifier']);
        unset($this['country']);

        $this->setWidget('address1', new sfWidgetFormInputText());
        $this->setWidget('address2', new sfWidgetFormInputText());
        $this->setWidget('city', new sfWidgetFormInputText());
        $this->setWidget('state', FormUtils::getStateWidget(true));

        $this->setValidators(array(
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 100),  array('required' => "Please enter a minyan name")),
            'address1'           => new sfValidatorString(array('required' => true)),
            'address2'           => new sfValidatorString(array('required' => false)),
            'city'               => new sfValidatorString(array('required' => true)),
            'state'              => FormUtils::getStateValidator(true),
            'zip'                =>  FormUtils::getZipValidator(true)
        ));

    }

}
