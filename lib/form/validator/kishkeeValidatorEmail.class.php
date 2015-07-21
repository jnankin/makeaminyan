<?php

class kishkeeValidatorEmail extends sfValidatorEmail
{

    protected function doClean($value)
    {
            $clean = trim(strtolower($value));
            return parent::doClean($clean);
    }
}