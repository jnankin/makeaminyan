<?php

class loginActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->redirectUrl = urldecode($request->getParameter('redirect'));
        
        MAMUtils::setView($this, array(
                'title' => 'Login',
                'mobileTemplate' => 'indexMobile',
                'selected' => 'Login'
        ));

        if ($this->getUser()->isAuthenticated()) {
            $this->redirect('dashboard/index');
        } else {
            if ($request->isMethod('post')) {
                //check password
                $username = $request->getParameter('username');
                $password = $request->getParameter('password');
                $rememberMe = $request->getParameter('remember_me');

                if (!$username || !$password) {
                    $this->getUser()->setFlash('loginError', 'Please enter a username and password.');
                    return sfView::SUCCESS;
                }

                if (AuthenticationUtils::signIn($username, $password, Utils::toBoolean($rememberMe))) {
                    if ($this->redirectUrl){
                        $this->redirect($this->redirectUrl);
                    }

                    $this->redirect('dashboard/index');
                }
                else if (AuthenticationUtils::userNotActive($username)) {
                    $flashText = 'Your account is deactivated.';

                    $flashText .= ' If you feel that you\'ve received this message in error, please send an email to <a href="mailto:makeaminyan1@gmail.com">makeaminyan1@gmail.com</a>.';

                    $this->getUser()->setFlash('loginError', $flashText);
                    return sfView::SUCCESS;
                } else {
                    $this->getUser()->setFlash('loginError', 'The username and password you provided were invalid.');
                    return sfView::SUCCESS;
                }
            } else {
                $requestUri = $_SERVER['REQUEST_URI'];
                if ($request->hasParameter('redirect')){
                    $this->redirectUrl = $request->getParameter('redirect');
                }
                else if (strpos($requestUri, '/login') !== 0){
                    $this->redirectUrl = $requestUri;
                }

                return sfView::SUCCESS;
            }
        }
    }

    public function executeLogout(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated()) {
            AuthenticationUtils::signOut();

            MAMUtils::setView($this, array(
                'title'=>'You have been logged out.',
                'mobileTemplate' => 'logoutMobile'
            ));

            return sfView::SUCCESS;
        }
        else
            $this->redirect('index/index');
    }

}
