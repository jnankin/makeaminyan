<?php

class AuthenticationUtils {

    public static function signIn($username, $password, $rememberMe) {
        $username = strtolower(trim($username));
        $rememberMe = Utils::toBoolean($rememberMe);


        if (is_numeric($username)) {
            $user = Doctrine::getTable('SfGuardUser')->findOneByPhone($username);
        } else {
            $user = Doctrine::getTable('SfGuardUser')->findOneByUsername($username);
        }

        // user exists?
        if ($user) {
            // password is ok?
            if ($user->checkPasswordByGuard($password)) {
                $sfUser = sfContext::getInstance()->getUser();
                $sfUser->signin($user, $rememberMe);
                return true;
            }
        }

        return false;
    }

    public static function userNotActive($username) {
        // user exists?
        $user = Doctrine::getTable('SfGuardUser')->findOneByUsername($username);
        return $user && !$user->getIsActive();
    }

    public static function signOut() {
        sfContext::getInstance()->getUser()->signOut();
    }

}

?>
