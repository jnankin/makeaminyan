<?php

/**
 * Blast
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    makeaminyan
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Blast extends BaseBlast {

    public function canAccess($guardUser) {
        if ($guardUser == null)
            return false;
        return $this->getMinyan()->isAdmin($guardUser->getId());
    }

    public function getEventTimeString() {
        if ($this->getEventTime() == null){
            return 'right now';
        }
        else {
            return ' at ' . date('h:i A', strtotime($this->getEventTime()));
        }
    }

    public function getAttendanceArray() {
        $responses = $this->getResponses();
        $totals = array('yes' => 0, 'no' => 0, 'maybe' => 0, 'noresponse' => 0);

        foreach($responses as $response){
            if ($response->getStatus() != null){
                $totals[$response->getStatus()] += $response->getFootprint();
            }
            else {
                $totals['noresponse']++;
            }
        }

        return $totals;
    }

    public function getTextMessage() {
        $message = 'Makeaminyan - ' . $this->getMinyan()->getName() . ' needs a ' . $this->getEventType() .
                ' minyan ' . $this->getEventTimeString() . '.';
        if ($this->getExtraReason()){
            $message .= 'Reason: ' . $this->getExtraReason();
        }

	$message = Utils::truncateStr($message, 140) . ' 1.yes 2.no 3.maybe';

        return $message;
    }

}
