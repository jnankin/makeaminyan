<?php

/**
 * minyanim actions.
 *
 * @package    makeaminyan
 * @subpackage minyanim
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class minyanimActions extends sfActions {

    private function setupView($options = array()) {
        $options['selected'] = 'Dashboard';
        MAMUtils::setView($this, $options);
    }

    public function executeSubscribers(sfWebRequest $request) {
        $this->minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'minyanId', true);
        $this->setupView(array('title' => 'Subscribers for ' . $this->minyan->getName()));
        $this->minyanUsers = Doctrine::getTable('MinyanUser')->findByMinyanId($this->minyan->getId());
    }

    public function executeAddNewSubscriber(sfWebRequest $request) {
        $this->minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'minyanId', true);
        $this->form = new SignupForm();
        unset($this->form['password']);

        if ($request->isMethod('post')){
            $this->form->bind($request->getParameter('signup'));

            if ($this->form->isValid()){
                $fields =  $this->form->getValues();

                $con = Doctrine::getConnectionByTableName('SfGuardUser');

                try {
                    $con->beginTransaction();
                    $this->logMessage("Executing signup for new user for minyan {$this->minyan->getName()}: {$fields['email']}", 'notice');

                    $sgu = new SfGuardUser();
                    $sgu->setFirstName($fields['first_name']);
                    $sgu->setLastName($fields['last_name']);
                    $sgu->setUsername($fields['email']);
                    $sgu->setEmailAddress($fields['email']);
                    $sgu->setPhone($fields['phone']);
                    $sgu->setPassword(sfConfig::get('app_temp_password'));
                    $sgu->setIsActive(true);
                    $sgu->save();

                    $contactMethods = $request->getParameter('contact_method');
                    foreach($contactMethods as $name=>$method) $contactMethods[$name] = Utils::toBoolean($method);

                    $minyanUser = new MinyanUser();
                    $minyanUser->setMinyanId($this->minyan->getId());
                    $minyanUser->setUserId($sgu->getId());
                    $minyanUser->setUsePhone($contactMethods['phone']);
                    $minyanUser->setUseSms($contactMethods['text']);
                    $minyanUser->setUseEmail($contactMethods['email']);
                    $minyanUser->save();

                    $con->commit();
                } catch (Exception $e) {
                    $con->rollback();
                    $this->logMessage("Problem when signing up user {$fields['email']}: {$e->getMessage()}", 'notice');
                    throw $e;
                }

                MAMUtils::sendInternalEmail("New Make a Minyan User Alert for minyan {$this->minyan->getName()}! - {$sgu->getFullName()}", "");

                //send email
                $options = array();
                $options['template'] = 'welcomeToMinyan';
                $options['subject'] = 'Welcome!';
                $options['minyan'] = $this->minyan;
                $options['user'] = $sgu;
                $options['minyanUser'] = $minyanUser;
                $options['first_name'] = $sgu->getFirstName();
                $options['to'] = $sgu->getUsername();
                EmailUtils::send($options);

                $this->logMessage('Welcome email sent to ' . $sgu->getUsername(), 'notice');

                $this->getUser()->setFlash('subscribersSuccess', 'Added ' . $sgu->getUsername() . ' successfully!');
                echo Utils::ajaxResponse(true, $this->minyan->getId());
                return sfView::NONE;

            }
        }
    }

    public function executeDeleteSubscribers(sfWebRequest $request) {
        $minyanUserIds = $request->getParameter('minyanUserIds');

        if (is_array($minyanUserIds)) {
            foreach ($minyanUserIds as $id) {
                $minyanUser = Doctrine::getTable('MinyanUser')->find($id);
                if (SecurityManager::verify($minyanUser)) {
                    $minyanUser->delete();
                }
            }
        }

        $this->getUser()->setFlash('subscribersSuccess', 'Deleted selected users successfully.');
        echo Utils::ajaxResponse(true);

        return sfView::NONE;
    }



    public function executeManage(sfWebRequest $request) {
        $this->minyan = Utils::extractDomainObjectFromRequest($request, 'Minyan', 'id', true);
        
        $this->setupView(array('title' => 'Update Minyan ' . $this->minyan->getName()));
        $this->guardUser = $this->getUser()->getGuardUser();

        $this->form = new MinyanForm($this->minyan);

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('minyan'));

            if ($this->form->isValid()) {

                $this->form->updateObject();
                $this->form->save();
                
                $this->getUser()->setFlash('dashboardSuccess', $this->minyan->getName() . ' has been updated successfully');
                $this->redirect('dashboard/index');
            }
        } 

        return sfView::SUCCESS;
    }

}
