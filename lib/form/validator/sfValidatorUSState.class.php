<?php

class sfValidatorUSState extends sfValidatorString
{
	protected function configure($options = array(), $messages = array())
	{
		parent::configure($options, $messages);
		$this->addMessage('max_length', 'Invalid state.');
		$this->addMessage('min_length', 'Invalid state.');
		$this->setOption('max_length', 2);
		$this->setOption('min_length', 2);
		
	}

	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value)
	{
		$clean = (string) $value;
		$clean = strtoupper($clean);
		
		if (!AdmorphousUtils::$usStates[$clean])
			//any error code is satisfactory
			throw new sfValidatorError($this, 'min_length', array());
		
		return parent::doClean($value);
	}
}
