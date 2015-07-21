<?php

class accountActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    private function setupView($options = array()) {
        $options['selected'] = 'My Account';
        MAMUtils::setView($this, $options);
    }

    public function executeIndex(sfWebRequest $request) {
        $this->setupView(array('title' => 'My account'));
        $this->guardUser = $this->getUser()->getGuardUser();

        $this->form = new AccountForm();
        $this->form->setExistingEmail($this->guardUser->getUsername());

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('account'));

            if ($this->form->isValid()) {
                $basicInfo = $this->form->getValues();

                $this->guardUser->setFirstName($basicInfo['first_name']);
                $this->guardUser->setLastName($basicInfo['last_name']);
                $this->guardUser->setUsername($basicInfo['email']);
                $this->guardUser->setEmailAddress($basicInfo['email']);
                $this->guardUser->setPhone($basicInfo['phone']);
                $this->guardUser->save();

                $this->getUser()->setFlash('accountSuccess', 'Account information has been updated successfully');
                $this->redirect('account/index');
            }
        } else {
            $this->form->setDefaults(array(
                "first_name" => $this->guardUser->getFirstName(),
                "last_name" => $this->guardUser->getLastName(),
                "email" => $this->guardUser->getUsername(),
                "phone" => $this->guardUser->getPhone()
            ));
        }

        return sfView::SUCCESS;
    }

    public function executeChangePassword(sfWebRequest $request) {
        $this->setupView(array('title' => 'Change Password'));
        $this->form = new ChangePasswordForm();
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('changePassword'));

            if ($this->form->isValid()) {
                $fields = $this->form->getValues();
                $this->getUser()->getGuardUser()->setPassword($fields['new_password']);
                $this->getUser()->getGuardUser()->save();
                $this->getUser()->setFlash('changePasswordFlash',
                        'Your password has been changed successfully.');
            }
        }

        return sfView::SUCCESS;
    }
}
