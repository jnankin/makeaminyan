<?php


class ChangePasswordForm extends BaseForm {

	public function configure(){
		$this->widgetSchema->setNameFormat('changePassword[%s]');
		$this->init();
	}

	public function init(){

		$passwordValidator = new sfValidatorString(array("required" => true, 'min_length' => 6));
		$passwordValidator->addMessage('min_length', "You password must be 6 characters or greater.");
		$passwordValidator->addMessage('required', "You must enter a password.");

		$existingPasswordValidator = new sfValidatorCallback(array(
            'callback'  => 'ChangePasswordForm::checkExistingPassword',
            'arguments' => array('username' => sfContext::getInstance()->getUser()->getUsername()),
		),
		array ('invalid' => 'Wrong password.'));
		$this->addWidget('old_password', new sfWidgetFormInputPassword(), $existingPasswordValidator);

		$this->addWidget('new_password', new sfWidgetFormInputPassword(), $passwordValidator);

		$this->addWidget('confirm_new_password', new sfWidgetFormInputPassword(), new sfValidatorString(array("required" => true), array("required" => "Please confirm your password.")));
		$this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'confirm_password'));

	}

	public static function checkExistingPassword($validator, $value, $arguments)
	{
		$sgu = Doctrine::getTable('SfGuardUser')->findOneByUsername($arguments['username']);
		
		if (!$sgu) throw new sfException('User ' . $arguments['username'] . ' does not exist! Impossible!');

		if (!$sgu->checkPassword($value))
		{
			throw new sfValidatorError($validator, 'invalid');
		}

		return $value;
	}



}

?>