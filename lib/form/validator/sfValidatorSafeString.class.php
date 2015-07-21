<?php

class sfValidatorSafeString extends sfValidatorString
{
	protected function configure($options = array(), $messages = array())
	{
		parent::configure($options, $messages);
    	$this->addOption('trim');
		$this->addOption('tolower');
		$this->addOption('strip');
	}

	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value)
	{
		$clean = (string) $value;
		
		$clean = Utils::safeSimpleStr($clean, $this->getOption('strip'), 
		  $this->getOption('trim'), $this->getOption('tolower'));
		
		return parent::doClean($clean);
	}
}
