<?php

class sfValidatorPermalink extends sfValidatorString {

    protected function configure($options = array(), $messages = array()) {
        parent::configure($options, $messages);
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($value) {
        $clean = strtolower(trim($value));
        
        if (!Utils::validPermalink($clean))
        //any error code is satisfactory
            throw new sfValidatorError($this, 'invalid', array());

        return parent::doClean($clean);
    }

}
