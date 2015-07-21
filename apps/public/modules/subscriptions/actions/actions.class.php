
<?php

class subscriptionsActions extends sfActions {
    private function setupView($options = array()) {
        $options['selected'] = 'Dashboard';
        MAMUtils::setView($this, $options);
    }

    public function executeFindMinyanByIdentifier(sfWebRequest $request) {
        $minyanIdentifier = Utils::requireParam($request, 'minyanIdentifier');
        $minyan = Doctrine::getTable("Minyan")->findOneByIdentifier(Utils::formatPermalink($minyanIdentifier));

        if (!$minyan){
            echo Utils::ajaxResponse(false, "No minyan could be found with this id.");
            return sfView::NONE;
        }

        $user = $this->getUser()->getGuardUser();

        if ($minyan->hasSubscriber($user->getId())){
            echo Utils::ajaxResponse(false, 'You are already subscribed to this minyan!');
            return sfView::NONE;
        }

        echo Utils::ajaxResponse(true, "", array(
            'id' => $minyan->getId(),
            'name' => $minyan->getName(),
        ));

        return sfView::NONE;
    }

    public function executeSubscribe(sfWebRequest $request) {
        $minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'id');

        if (!$minyan){
            echo Utils::ajaxResponse(false, "No minyan could be found with this id.");
            return sfView::NONE;
        }

        $user = $this->getUser()->getGuardUser();

        if ($minyan->hasSubscriber($user->getId())){
            echo Utils::ajaxResponse(false, 'You are already subscribed to this minyan!');
            return sfView::NONE;
        }

        $contactMethods = array();
        $contactMethods['phone'] = Utils::toBoolean($request->getParameter('use_phone'));
        $contactMethods['text'] = Utils::toBoolean($request->getParameter('use_text'));
        $contactMethods['email'] = Utils::toBoolean($request->getParameter('use_email'));


        if ($contactMethods['phone'] == false && $contactMethods['text'] == false && $contactMethods['email'] == false){
            echo Utils::ajaxResponse(false, 'You must select at least one contact method!');
            return sfView::NONE;
        }

        $minyanUser = new MinyanUser();
        $minyanUser->setMinyanId($minyan->getId());
        $minyanUser->setUserId($user->getId());
        $minyanUser->setUsePhone($contactMethods['phone']);
        $minyanUser->setUseSms($contactMethods['text']);
        $minyanUser->setUseEmail($contactMethods['email']);
        $minyanUser->save();

        echo Utils::ajaxResponse(true);
        return sfView::NONE;


    }

    public function executeUpdateContactMethod(sfWebRequest $request) {
        $minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'minyanId', false);
        $minyanUser = Doctrine::getTable('MinyanUser')->getMinyanUser($minyan->getId(), $this->getUser()->getId());

        if ($minyanUser){
            $contactMethods = $request->getParameter('contact_method');
            foreach($contactMethods as $name=>$method) $contactMethods[$name] = Utils::toBoolean($method);
            
            $minyanUser->setUsePhone($contactMethods['phone']);
            $minyanUser->setUseSms($contactMethods['text']);
            $minyanUser->setUseEmail($contactMethods['email']);
            $minyanUser->save();

            $this->getUser()->setFlash('subscriptionsSuccess', "Your contact methods for " . $minyan->getName() . " have been updated.");
        }

        $this->redirect('subscriptions/index?minyanId=' . $minyan->getId());

    }
    public function executeUnsubscribe(sfWebRequest $request) {
        $minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'minyanId', false);

        $minyanUser = Doctrine::getTable('MinyanUser')->getMinyanUser($minyan->getId(), $this->getUser()->getId());

        if ($minyanUser){
            $minyanUser->delete();    
        }

        echo Utils::ajaxResponse(true);

        return sfView::NONE;
    }

    public function executeIndex(sfWebRequest $request) {
        $this->minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'minyanId', false);
        $this->minyanUser = Doctrine::getTable('MinyanUser')->getMinyanUser($this->minyan->getId(), $this->getUser()->getId());
        $this->setupView();
        
        if (!$this->minyanUser){
            return sfView::ERROR;
        }

        $this->minyanResponses = Doctrine::getTable("BlastResponse")->createQuery()
                ->select('br.*, b.*')->from('BlastResponse')
                ->leftJoin('br.Blast b')
                ->where('b.minyan_id = ?', $this->minyan->getId())
                ->andWhere('br.user_id = ?', $this->getUser()->getId())
                ->orderBy('br.updated_at DESC');

    }

}
