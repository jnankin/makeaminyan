<?php

class sfValidatorTimeString extends sfValidatorBase
{
	protected function configure($options = array('start_time' => '12:00am', 'end_time' => '11:59pm'), $messages = array())
	{
	    $this->addMessage('start_time', '"%value%" cannot be before %start_time%.');
    	$this->addMessage('end_time', '"%value%" cannot be after %end_time%.');

    	$this->addOption('start_time');
		$this->addOption('end_time');
		
		$this->setInvalidMessage('Invalid time.');
		
		$this->setOption('start_time', DateTimeUtils::parseTime($options['start_time']));
		$this->setOption('end_time', DateTimeUtils::parseTime($options['end_time']));
	}

	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value)
	{
		$clean = (string) $value;
			
		if (!DateTimeUtils::validTimeString($value))
			throw new sfValidatorError($this, 'invalid');
		
		$clean = DateTimeUtils::formatTime(DateTimeUtils::parseTime($value));
		$cleanedSeconds = DateTimeUtils::getTimeSeconds($value);
		
		if ($cleanedSeconds < DateTimeUtils::getTimeSeconds($this->getOption('startTime'))){
			throw new sfValidatorError($this, 'start_time', array('value'=> $clean, 'start_time'=>$this->getOption('start_time')));
		}
		
		if ($cleanedSeconds > DateTimeUtils::getTimeSeconds($this->getOption('endTime'))){
			throw new sfValidatorError($this, 'end_time', array('value'=> $clean, 'end_time'=>$this->getOption('end_time')));
		}
		
		return $clean;
	}
}
