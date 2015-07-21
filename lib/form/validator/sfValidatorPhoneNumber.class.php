<?php

class sfValidatorPhoneNumber extends sfValidatorString {

    protected function configure($options = array(), $messages = array()) {
        parent::configure($options, $messages);
        $this->addOption('max_length', 10);
        $this->addOption('min_length', 10);
        $this->addMessage('max_length', 'The phone number you entered was too long.');
        $this->addMessage('min_length', 'The phone number you entered was too short.');
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($value) {
        return parent::doClean(self::cleanProcess($value));
    }

    public static function cleanProcess($value) {
        $clean = (string) $value;
        $clean = preg_replace('/[^\d]/', '', $clean);
        $clean = Utils::safeSimpleStr($clean);
        return $clean;
    }

}
