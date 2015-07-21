<?php

class myUser extends sfGuardSecurityUser {

    private $messages = array();
    protected $mobileDetector = null;

    public function hasCredential($credential, $useAnd = true) {
        if (!$this->getGuardUser())
            return false;

        $allPermissions = $this->getGuardUser()->getAllPermissions();
        if ($allPermissions[$credential])
            return true;

        return parent::hasCredential($credential, $useAnd);
    }

    public function hasPermission($permission, $useAnd = true) {
        return $this->hasCredential($permission, $useAnd);
    }

    public function signIn($user, $remember = false, $con = null) {
        parent::signIn($user, $remember, $con);
        $this->addCredentials($user->getGroups());
    }

    public function hasMessage($messageName) {
        return isset($this->messages[$messageName]);
    }

    public function getMessage($messageName) {
        return $this->messages[$messageName];
    }

    public function setMessage($messageName, $value) {
        $this->messages[$messageName] = $value;
    }

    public function getFirstName() {
        return $this->getGuardUser()->getFirstName();
    }

    public function getLastName() {
        return $this->getGuardUser()->getFirstName();
    }

    public function getId() {
	if ($this->getGuardUser())
	  return $this->getGuardUser()->getId();
    }

    public function getFullName() {
        return $this->getGuardUser()->getFirstName() . ' ' . $this->getGuardUser()->getLastName();
    }

    public function getEmail() {
        return $this->getGuardUser()->getUsername();
    }

    public function getPhone() {
        return $this->getGuardUser()->getPhone();
    }

    public function getMobileDetector() {
        if ($this->mobileDetector == null) {
            $this->mobileDetector = new MobileDetector();
        }
        return $this->mobileDetector;
    }

    public function isMobile() {
        $detector = $this->getMobileDetector();
        return $detector->isMobile();
    }

    public function getDeviceType() {

        $detector = $this->getMobileDetector();
        if (!$detector->isMobile())
            return null;
        else if ($detector->isAndroid())
            return 'android';
        else if ($detector->isIphone())
            return 'iphone';
        else if ($detector->isBlackberry())
            return 'blackberry';
        else if ($detector->isPalm())
            return 'palm';
        else if ($detector->isWindows())
            return 'windows';
        else if ($detector->isGeneric())
            return 'other';
        return null;
    }

}
