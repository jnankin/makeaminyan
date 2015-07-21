<?php

class AccountForm extends BaseForm {

    private $existingEmail;
    
    public function configure() {
        $this->widgetSchema->setNameFormat('account[%s]');
        $this->init();
    }

    public function init() {
        /*
         * BASIC INFO
         */
        
        $this->addWidget('first_name', new sfWidgetFormInputText(), new sfValidatorString(array("required" => true), array("required" => "Please enter your first name.")));
        $this->addWidget('last_name', new sfWidgetFormInputText(), new sfValidatorString(array("required" => true), array("required" => "Please enter your last name.")));
        $this->addWidget('email', new sfWidgetFormInputText(), new sfValidatorEmail(array("required" => true), array("required" => "Please enter a valid email address.")));
        $this->addWidget('phone', new sfWidgetFormInputText(), FormUtils::getPhoneValidator(true));
    }

    public function isValid() {
        $check = parent::isValid();

        $values = $this->getTaintedValues();

        //validate email available
        if ($values['email'] != $this->existingEmail && Doctrine::getTable('SfGuardUser')->emailExists($values['email'])) {
            $this->addCustomError('This email address is already used by another account.', 'email');

            $check = false;
        }

        return $check;
    }

    public function setExistingEmail($existingEmail){
        $this->existingEmail = $existingEmail;
    }

}
?>