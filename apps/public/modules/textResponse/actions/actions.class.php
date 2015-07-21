<?php

/**
 * textResponse actions.
 *
 * @package    makeaminyan
 * @subpackage textResponse
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class textResponseActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        if ($request->isMethod('post')){
            $from = $request->getParameter('From');
            $body = $request->getParameter('Body');
            
            if (!Utils::isEmptyStr($from) && !Utils::isEmptyStr($body)){
                if (Utils::startsWith($from, "+1")) $from = substr($from, 2);

                //look up the user by phone number
                $guardUser = Doctrine::getTable('SfGuardUser')->findOneByPhone($from);
                if (!$guardUser){
                    $this->logMessage('Could not find user with phone number ' . $from, 'notice');
                    return sfView::NONE;
                }


                //get the last response
                $response = Doctrine::getTable('BlastResponse')->createQuery()->where('user_id = ?', $guardUser->getId())
                        ->orderBy('updated_at desc')->fetchOne();

                if ($response) {
                    //get status and additional
                    $statusAndAdditional = $this->getStatusAndAdditional($body);
                    if ($statusAndAdditional && array_search($statusAndAdditional['status'], array('yes', 'no', 'maybe')) !== false){
                        $response->setStatus($statusAndAdditional['status']);
                    }

                    if ($statusAndAdditional && $statusAndAdditional['status'] == 'yes' && $statusAndAdditional['additional']){
                        $response->setAdditional($statusAndAdditional['additional']);
                    }
                    else {
                        $response->setAdditional(0);
                    }

                    $response->save();
                    $this->logMessage("Set status={$response->getStatus()} and additional={$response->getAdditional()} for text message responseId={$response->getId()} for {$guardUser->getUsername()}", 'notice');
                }
            }
       }
       return sfView::NONE;
    }

    public function getStatusAndAdditional($bodyString){
        $results = array();
        $bodyString = strtolower(trim($bodyString));
        preg_match("/^(1|2|3|yes|no|maybe)\s*(\+[1-9])?$/", $bodyString, $results);


        if (count($results) > 0){
            $statusAndAdditional = array();

            if (is_numeric($results[1])){
                $choices = array('', 'yes', 'no', 'maybe');
                $statusAndAdditional['status'] = $choices[$results[1]];
            }
            else {
                $statusAndAdditional['status'] = $results[1];
            }


            if ($results[2]){
                $statusAndAdditional['additional'] = substr($results[2],1);
            }

            return $statusAndAdditional;
        }
        else {
            return null;
        }
        
    }

}
