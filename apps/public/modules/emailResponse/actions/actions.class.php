<?php

class emailResponseActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->blastResponse = Utils::extractDomainObjectFromRequest($request, 'BlastResponse', 'responseId');

        MAMUtils::setView($this, array('title' => 'Thanks for your response!'));

        if ($request->hasParameter('status') && array_search($request->getParameter('status'), array('yes', 'no', 'maybe')) !== false){
            $this->blastResponse->setStatus($request->getParameter('status'));

            if ($request->getParameter('status') !== 'yes'){
                $this->blastResponse->setAdditional(0);
            }

            $this->blastResponse->save();
        }
        
        if ($request->hasParameter('additional') && is_numeric($request->getParameter('additional')) && $request->getParameter('additional') < 10){
            $this->blastResponse->setAdditional($request->getParameter('additional'));
            $this->blastResponse->save();
            $this->getUser()->setMessage('additionalSuccess', 'Additional number of people stored successfully');
        }

        $this->minyan =	$this->blastResponse->getBlast()->getMinyan()->getName();
	$this->eventType = $this->blastResponse->getBlast()->getEventType();
	$this->eventTime = $this->blastResponse->getBlast()->getEventTimeString();
    }

}
