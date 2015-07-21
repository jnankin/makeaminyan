<?php

class sfValidatorMoney extends sfValidatorInteger
{
	/**
	 * @see sfValidatorBase
	 */
	protected function doClean($value)
	{
		$clean = intval($value * 100);
		return parent::doClean($clean);
	}

}
