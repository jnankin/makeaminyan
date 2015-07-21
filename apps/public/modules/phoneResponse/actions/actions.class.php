<?php

class phoneResponseActions extends sfActions {

    const YES = 1;
    const NO = 2;
    const MAYBE = 3;

    public function executeIndex(sfWebRequest $request) {
        if ($request->isMethod('post')){
            $from = $request->getParameter('From');
            
            if (!Utils::isEmptyStr($from)){
                if (Utils::startsWith($from, "+1")) $from = substr($from, 2);

                //look up the user by phone number
                $guardUser = Doctrine::getTable('SfGuardUser')->findOneByPhone($from);
                if ($guardUser){
		        //get the last response
		        $response = Doctrine::getTable('BlastResponse')->createQuery()->where('user_id = ?', $guardUser->getId())
		                ->orderBy('updated_at desc')->fetchOne();

		        if ($response) {
				$this->redirect('phoneResponse/intro?responseId=' . $response->getId());
		        }
		}
            }
       }
?>
<Response>
     <Say>Thanks for calling Make A Minyan dot com.  Please visit us online at Make A Minyan dot com.  Thank you!  Goodbye.</Say>
     <Hangup />
</Response>
<?
       return sfView::NONE;
    }

    public function executeIntro(sfWebRequest $request) {
        $this->blastResponse = Utils::extractDomainObjectFromRequest($request, 'BlastResponse', 'responseId');
        $this->minyanName = $this->blastResponse->getBlast()->getMinyan()->getName();
	$this->eventType = $this->blastResponse->getBlast()->getEventType();
	$this->eventTime = $this->blastResponse->getBlast()->getEventTimeString();
        $this->setLayout(false);
    }

    public function executeRecordResponse(sfWebRequest $request){
        $this->blastResponse = Utils::extractDomainObjectFromRequest($request, 'BlastResponse', 'responseId');
        $this->choice = $request->getParameter('Digits');

        if ($this->choice == self::YES) $this->blastResponse->setStatus('yes');
        else if ($this->choice == self::NO) $this->blastResponse->setStatus('no');
        else if ($this->choice == self::MAYBE) $this->blastResponse->setStatus('maybe');

        if ($this->choice !== self::YES){
            $this->blastResponse->setAdditional(0);
        }
        
        $this->blastResponse->save();

        $this->setLayout(false);
    }

    public function executeAcceptAdditional(sfWebRequest $request){
        $this->blastResponse = Utils::extractDomainObjectFromRequest($request, 'BlastResponse', 'responseId');
        $this->numAdditional = $request->getParameter('Digits');
        $this->blastResponse->setAdditional($this->numAdditional);
        $this->blastResponse->save();

        $this->setLayout(false);
    }

}
