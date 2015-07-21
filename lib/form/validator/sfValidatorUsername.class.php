<?php

class sfValidatorUsername extends sfValidatorString
{
	protected function configure($options = array(), $messages = array())
	{
		parent::configure($options, $messages);
	}

	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value)
	{
		$clean = (string) $value;
		$clean = Utils::safeSimpleStr($clean);
		$clean = strtolower($clean);
		parent::doClean($clean);
		
		if (!Utils::validUsername($clean))
		  throw new sfValidatorError($this, 'invalid', array('value'=> $clean));
		  
		return $clean;
	}
}
