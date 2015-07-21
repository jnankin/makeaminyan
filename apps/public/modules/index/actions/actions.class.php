<?php

/**
 * index actions.
 *
 * @package    makeaminyan
 * @subpackage index
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class indexActions extends sfActions {

    public function setupPage($options = array()) {
        MAMUtils::setView($this, $options);
    }

    public function executeIndex(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) $this->redirect('dashboard/index');
        else if ($this->getUser()->isMobile()) $this->redirect('index/indexMobile');

        $this->form = new SignupForm();
        
        return sfView::SUCCESS;

    }

    public function executeIndexMobile(sfWebRequest $request) {
        $this->setLayout('mobile');
        return sfView::SUCCESS;
    }

}
