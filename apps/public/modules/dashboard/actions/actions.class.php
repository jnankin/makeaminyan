<?php

class dashboardActions extends sfActions {

    private function setupView($options = array()) {
        $options['selected'] = 'Dashboard';
        MAMUtils::setView($this, $options);
    }

    public function executeIndex(sfWebRequest $request) {
        $this->setupView(array('title' => 'Dashboard', 'mobileTemplate' => 'indexMobile'));

        $this->minyanim = Doctrine_Query::create()->from("MinyanUser mu")->
                where("mu.user_id = ?", $this->getUser()->getGuardUser()->getId())->execute();


    }

}
