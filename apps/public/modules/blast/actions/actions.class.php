<?php

class blastActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    private function setupView($options = array()) {
        $options['selected'] = 'Dashboard';
        MAMUtils::setView($this, $options);
    }

    public function executeList(sfWebRequest $request) {
        $this->minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'minyanId', true);
        $this->setupView(array('title' => 'Minyan Blasts for ' . $this->minyan->getName()));
        $this->blasts = Doctrine::getTable('Blast')->createQuery()->
                where('minyan_id = ?', $this->minyan->getId())->orderBy('created_at desc')->execute();
    }

    public function executeDashboard(sfWebRequest $request) {
        $this->blast = Utils::extractDomainObjectFromRequest($request, 'Blast', 'blastId', true);
        $this->setupView(array('title' => 'Minyan Blast for ' . $this->blast->getMinyan()->getName()));
    }

    public function executeGetResponses(sfWebRequest $request) {
        $blast = Utils::extractDomainObjectFromRequest($request, 'Blast', 'blastId', true);
        $responses = $blast->getResponses();

        $data = array();
        $responseData = array();
        $data['totals'] = $blast->getAttendanceArray();

        foreach($responses as $response){
            $responseData[] = array(
                'name' => $response->getUser()->getFullname(),
                'status' => $response->getStatus(),
                'footprint' => $response->getFootprint()
            );
        }

        $data['responseData'] = $responseData;
        echo Utils::ajaxResponse(true, '', $data);
        return sfView::NONE;
    }

    public function executeCreate(sfWebRequest $request) {
        $this->minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'minyanId', true);
        $this->setupView(array('title' => 'Create a minyan blast'));
        $this->user = $this->getUser()->getGuardUser();

        $this->form = new BlastForm();

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('blast'));

            if ($this->form->isValid()) {
                $this->form->updateObject();
                $blast = $this->form->getObject();

                $blast->setInitiatingUserId($this->user->getId());
                $blast->setMinyanId($this->minyan->getId());
                $blast->save();

                BlastManager::fireBlast($blast);

                $this->redirect('blast/dashboard?blastId=' . $blast->getId());
            }
        } 

        return sfView::SUCCESS;
    }

}
