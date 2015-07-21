<?php

class BaseForm extends sfFormSymfony {

    public function addWidget($name, sfWidgetForm $widget, $validator=null) {
        $this->widgetSchema[$name] = $widget;

        if ($validator != null)
            $this->validatorSchema[$name] = $validator;
        else
            $this->validatorSchema[$name] = new sfValidatorPass();
    }

    public function addCustomError($error, $field) {
        $this->getErrorSchema()->addError(new sfValidatorError(new sfValidatorPass(), $error), $field);
    }

}
