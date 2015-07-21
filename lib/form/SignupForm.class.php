<?php


class SignupForm extends BaseForm {

    public function configure() {
        $this->widgetSchema->setNameFormat('signup[%s]');
        $this->init();
    }

    public function init() {

        $this->addWidget('first_name', new sfWidgetFormInputText(), new sfValidatorString(array("required" => true), array("required" => "Please enter your first name.")));
        $this->addWidget('last_name', new sfWidgetFormInputText(), new sfValidatorString(array("required" => true), array("required" => "Please enter your last name.")));
        $this->addWidget('phone', new sfWidgetFormInputText(), FormUtils::getPhoneValidator());

        $this->addWidget('email', new sfWidgetFormInputText(), FormUtils::getEmailValidator());

        $passwordValidator = new sfValidatorString(array("required" => true, 'min_length' => 6), array(
            'min_length' =>"Your password must be at least 6 characters.",
            "required" => "Please enter a password.")
        );

        $this->addWidget('password', new sfWidgetFormInputPassword(), $passwordValidator);


    }

    public function isValid() {
        $check = parent::isValid();

        $values = $this->getTaintedValues();

        //validate email available
        if (Doctrine::getTable('SfGuardUser')->emailExists($values['email'])) {
            $this->addCustomError('This email address is already used by another account.', 'email');

            $check = false;
        }

        //validate phone available
        if (Doctrine::getTable('SfGuardUser')->phoneExists($values['phone'])) {
            $this->addCustomError('This phone number is already used by another account.', 'phone');

            $check = false;
        }

        return $check;
    }

}

?>