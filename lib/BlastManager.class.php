<?php

require_once('twilio.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BlastManager
 *
 * @author jnankin
 */
require_once('twilio.php');

class BlastManager {

    public static function fireBlast(Blast $blast) {

        //dont do anything if we've already fired
        if ($blast->getHasFired())
            return;

        $blast->setHasFired(true);
        $blast->save();

        $minyan = $blast->getMinyan();

        //loop through the minyan subscribers, generate a response, and send notifications
        $phoneCounter = 0;
        $emailCounter = 0;
        $textCounter = 0;

        $subscribers = $minyan->getUsers();

        foreach ($subscribers as $subscriber) {
            $response = new BlastResponse();
            $response->setBlastId($blast->getId());
            $response->setUserId($subscriber->getUserId());
            $response->save();

            if ($subscriber->getUsePhone()) {
                self::createPhoneResponse($response);
                $phoneCounter++;
            } else if ($subscriber->getUseSms()) {
                self::createTextResponse($response);
                $textCounter++;
            }

            if ($subscriber->getUseEmail()) {
                self::createEmailResponse($response);
                $emailCounter++;
            }

        }

        MAMUtils::sendInternalEmail("Minyan Blast - {$minyan->getName()}", $blast->getTextMessage() . 
                "<br><ul><li>Emails: $emailCounter</li><li>Phone Calls: $phoneCounter</li><li>Texts: $textCounter</li></ul>");

        //update the minyan resource counters
        $minyan->setNumberEmails($minyan->getNumberEmails() + $emailCounter);
        $minyan->setNumberTexts($minyan->getNumberTexts() + $textCounter);
        $minyan->setNumberPhoneCalls($minyan->getNumberPhoneCalls() + $phoneCounter);
        $minyan->save();

        $blast->setNumberEmails($blast->getNumberEmails() + $emailCounter);
        $blast->setNumberTexts($blast->getNumberTexts() + $textCounter);
        $blast->setNumberPhoneCalls($blast->getNumberPhoneCalls() + $phoneCounter);
        $blast->save();
    }

    public static function createPhoneResponse(BlastResponse $response) {
        $user = $response->getUser();

        Utils::logNotice('Sending phone response for ' .
                        $response->getBlast()->getMinyan()->getName() .
                        ' to ' . $user->getFullname() .
                        ' (' . $user->getPhone() . ')');

        $data = array(
            "From" => sfConfig::get("app_twilio_caller_id"), // Outgoing Caller ID
            "To" => "1" . $user->getPhone(), // The phone number you wish to dial
            "Url" => sfConfig::get("app_url_prefix") . "phoneResponse/intro?responseId=" . $response->getId(),
            "IfMachine" => "Continue"
        );

        
        $client = new TwilioRestClient(sfConfig::get("app_twilio_sid"), sfConfig::get("app_twilio_auth_token"));
        $urlPrefix = "/" . sfConfig::get("app_twilio_api_version") . "/Accounts/" . sfConfig::get("app_twilio_sid") . "/";
        $response = $client->request($urlPrefix . "Calls", "POST", $data);
    }

    public static function createTextResponse(BlastResponse $response) {
        $user = $response->getUser();
        
        Utils::logNotice('Sending text response for ' .
                        $response->getBlast()->getMinyan()->getName() .
                        ' to ' . $response->getUser()->getFullname() .
                        ' (' . $response->getUser()->getPhone() . ')');

        $data = array(
            "From" => sfConfig::get("app_twilio_caller_id"), // Outgoing Caller ID
            "To" => "1" . $user->getPhone(), // The phone number you wish to dial
            "Body" => $response->getBlast()->getTextMessage()
        );

        $client = new TwilioRestClient(sfConfig::get("app_twilio_sid"), sfConfig::get("app_twilio_auth_token"));
        $urlPrefix = "/" . sfConfig::get("app_twilio_api_version") . "/Accounts/" . sfConfig::get("app_twilio_sid") . "/";
        $client->request($urlPrefix . "SMS/Messages", "POST", $data);
    }

    public static function createEmailResponse(BlastResponse $response) {
        Utils::logNotice('Sending email response for ' .
                        $response->getBlast()->getMinyan()->getName() .
                        ' to ' . $response->getUser()->getFullname() .
                        ' (' . $response->getUser()->getUsername() . ')');

        $blast = $response->getBlast();

        //send email
        $options = array();
        $options['template'] = 'blast';
        $options['subject'] = 'Minyan Needed! ' . $blast->getEventType() . ' - ' . $blast->getMinyan()->getName();
        $options['blastResponse'] = $response;
        $options['blast'] = $blast;
        $options['to'] = $response->getUser()->getUsername();
        EmailUtils::send($options);
    }

}

?>
