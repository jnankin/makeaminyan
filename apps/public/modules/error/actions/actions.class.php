<?php

class errorActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function setupPage($options = array()) {
        MAMUtils::setView($this, $options);
    }


    public function executeError404(sfWebRequest $request) {
        $this->setupPage(array('title' => 'The page you requested could not be found'));
        return sfView::SUCCESS;
    }

    public function executeSecureError(sfWebRequest $request) {
        $this->setupPage(array('title' => 'We\'ve encountered an error'));
        return sfView::SUCCESS;
    }

    public function executeUnavailable(sfWebRequest $request) {
        $this->setupPage(array('title' => 'The page you requested is unavailable'));
        $this->setupPage();
        return sfView::SUCCESS;
    }

}
