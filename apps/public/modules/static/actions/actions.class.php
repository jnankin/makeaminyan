<?php

/**
 * static actions.
 *
 * @package    makeaminyan
 * @subpackage static
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class staticActions extends sfActions {

    public function setupPage($options = array()) {
        MAMUtils::setView($this, $options);
    }

    public function executeContact(sfWebRequest $request) {
        $this->setupPage(array('title' => 'Contact us', 'selected' => 'Contact'));
    }

    public function executeAbout(sfWebRequest $request) {
        $this->setupPage(array('title' => 'About us', 'selected' => 'About'));
    }

    public function executeSubmitMinyan(sfWebRequest $request) {
        $this->setupPage(array('title' => 'Submit a Minyan', 'selected' => 'Submit a minyan'));
    }

}
