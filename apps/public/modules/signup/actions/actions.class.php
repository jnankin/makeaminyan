<?php

/**
 * signup actions.
 *
 * @package    makeaminyan
 * @subpackage signup
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class signupActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->form = new SignupForm();
        
        if ($request->isMethod('post')){
            $this->form->bind($request->getParameter('signup'));
            
            if ($this->form->isValid()){
                $fields =  $this->form->getValues();

                $con = Doctrine::getConnectionByTableName('SfGuardUser');

                try {
                    $con->beginTransaction();
                    $this->logMessage("Executing signup for new user: {$fields['email']}", 'notice');

                    $sgu = new SfGuardUser();
                    $sgu->setFirstName($fields['first_name']);
                    $sgu->setLastName($fields['last_name']);
                    $sgu->setUsername($fields['email']);
                    $sgu->setEmailAddress($fields['email']);
                    $sgu->setPhone($fields['phone']);
                    $sgu->setPassword($fields['password']);
                    $sgu->setIsActive(true);
                    $sgu->save();
                    $con->commit();
                } catch (Exception $e) {
                    $con->rollback();
                    $this->logMessage("Problem when signing up user {$fields['email']}: {$e->getMessage()}", 'notice');
                    throw $e;
                }

                MAMUtils::sendInternalEmail("New Make a Minyan User Alert! - {$sgu->getFullName()}, Plan: {$this->plan['name']}", "");

                //send email
                $options = array();
                $options['template'] = 'welcome';
                $options['subject'] = 'Welcome!';
                $options['first_name'] = $sgu->getFirstName();
                $options['to'] = $sgu->getUsername();
                EmailUtils::send($options);

                $this->logMessage('Welcome email sent to ' . $sgu->getUsername(), 'notice');

                $this->redirect('signup/thanks');
                
            }
        }
    }

    public function executeThanks(sfWebRequest $request) {

    }
}
