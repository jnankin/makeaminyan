<?php

class MAMUtils {

    /*******************************************
     * EMAIL
     *******************************************/

    public static function sendInternalEmail($subject, $body){
        $mailerOptions = sfConfig::get('app_mailer_defaults');
        if (!$mailerOptions['send_email']) return;

        $mailer = sfContext::getInstance()->getMailer();
        $message = Swift_Message::newInstance();

        $message->setSubject($subject);
        $message->setBody($body);
        $message->addPart($body, 'text/html');
        $message->setFrom(sfConfig::get('app_emails_internal'));
        $message->setTo(array(sfConfig::get('app_emails_internal')));
        $mailer->send($message);
    }

    /*
     * Sets up the response, layout, and template for a kishkee request
     * Options:
     * menu - what menu bar to display
     * selected - what item is selected in the menu
     * title - the title of the page
     * noGS - turn off get satisfaction for this request
     * mobileTemplate - the template to use when the request is mobile
     * forceMobile - force the request to display as if it were on a mobile phone
     * mobileLayout - the layout to use when the request is mobile
     * layout - override the layout for the request
     */
    public static function setView($action, $options = array()) {
        $response = $action->getResponse();
        $user = $action->getUser();

        if ($options['menu']) {
            $response->menu = $options['menu'];
        } else {
            if ($user->isAuthenticated()) {
                    $response->menu = MenuBar::$CLIENT;
            } else {
                $response->menu = MenuBar::$EXTERNAL;
            }
        }

        if ($options['selected']) {
            $response->menu[$options['selected']]['selected'] = true;
        }

        //if user is mobile, always set to kishkee, for bookmarking purposes
        if ($options['title']) {
            $response->setTitle($options['title'] . ' | Make a minyan');
        } else {
            $response->setTitle('Make a minyan');
        }

        if ($action->getUser()->isMobile() || $options['forceMobile']) {
            $action->setLayout('mobile');

            if ($options['mobileTemplate'] !== null) {
                $action->setTemplate($options['mobileTemplate']);
            }

            if ($options['mobileLayout'] !== null){
                $action->setLayout($options['mobileLayout']);
            }
        }
        else if (!$action->getUser()->isMobile()) {
            if ($options['layout'] !== null){
                $action->setLayout($options['layout']);
            }
        }
    }

    /*******************************************
     * FLASHES AND BETWEEN REQUEST MESSAGING
     *******************************************/

    //message types
    const SUCCESS = 'success';
    const ERROR = 'error';
    const CUSTOM = 'custom';

    public static function writeFlashBlock($flashName, $additionalAttributes = "") {
        self::writeSuccessFlash($flashName . "Success", $additionalAttributes);
        self::writeErrorFlash($flashName . "Error", $additionalAttributes);
    }

    public static function writeSuccessFlash($flashName, $additionalAttributes = "") {
        $sf_user = sfContext::getInstance()->getUser();
        if ($sf_user->hasFlash($flashName) || $sf_user->hasMessage($flashName)) {
            echo "<div id='$flashName' class='successMessage' $additionalAttributes>";
        }

        if ($sf_user->hasFlash($flashName))
            echo $sf_user->getFlash($flashName);
        if ($sf_user->hasFlash($flashName) && $sf_user->hasMessage($flashName))
            echo "<br>";
        if ($sf_user->hasMessage($flashName))
            echo $sf_user->getMessage($flashName);

        if ($sf_user->hasFlash($flashName) || $sf_user->hasMessage($flashName)) {
            echo "</div>";
            echo '<script type="text/javascript">$().ready(function(){setTimeout(function(){$("#' . $flashName . '").fadeOut("slow");}, 5000);})</script>';
        }
    }

    public static function writeCustomFormErrors($forms = array()) {
        if (!is_array($forms))
            $forms = array($forms);
        $hasCustomErrors = false;

        foreach ($forms as $form) {
            $hasCustomErrors |= $form->hasCustomErrors();
        }

        if ($hasCustomErrors) {
            echo "<div class='errorMessage'><ul>";
            foreach ($forms as $form) {
                $errors = $form->getCustomErrors();
                foreach ($error as $errors) {
                    echo "<li>$error</li>";
                }
            }
            echo "</ul></div>";
        }
    }

    public static function writeErrorFlash($flashName, $additionalAttributes = "") {
        $sf_user = sfContext::getInstance()->getUser();
        if ($sf_user->hasFlash($flashName) || $sf_user->hasMessage($flashName)) {
            echo "<div id='$flashName' class='errorMessage', $additionalAttributes>";
        }

        if ($sf_user->hasFlash($flashName))
            echo $sf_user->getFlash($flashName);
        if ($sf_user->hasFlash($flashName) && $sf_user->hasMessage($flashName))
            echo "<br>";
        if ($sf_user->hasMessage($flashName))
            echo $sf_user->getMessage($flashName);

        if ($sf_user->hasFlash($flashName) || $sf_user->hasMessage($flashName)) {
            echo "</div>";
        }
    }

    public static function showFlash($flashName, $type = self::ERROR, $customClass = '', $rest = '') {
        if (sfContext::getInstance()->getUser()->hasFlash($flashName)) {

            switch ($type) {
                case self::ERROR: echo "<div class='errorMessage $customClass' $rest>";
                    break;
                case self::SUCCESS: echo "<div class='successMessage $customClass' $rest>";
                    break;
                case self::CUSTOM: echo "<div class='$customClass' $rest>";
                    break;
            }

            echo sfContext::getInstance()->getUser()->getFlash($flashName);
            echo "</div>";
        }
    }

    public static function errorFlash($flashName) {
        self::showFlash($flashName, self::ERROR);
    }

    public static function successFlash($flashName) {
        self::showFlash($flashName, self::SUCCESS);
    }


}

