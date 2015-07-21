<?php 


class SecurityManager {

    //objects passed to this function are verified that they can be accessed by the current user.
    public static function verifyOrForward($action, $object) {
        if (!$object->canAccess(sfContext::getInstance()->getUser()->getGuardUser())) {
            if (sfContext::getInstance()->getUser()->isAuthenticated())
                $action->forward('error', 'secureError');
            else
                $action->redirect('login/index?redirect=' . ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        }
    }

    public static function verify($object) {
        if (!$object->canAccess(sfContext::getInstance()->getUser()->getGuardUser())) {
            return false;
        }
        return true;
    }
}



?>