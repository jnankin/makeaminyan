<?php 
class FormUtils {

    const CREATE = 'create';
    const UPDATE = 'update';
    const COPY = 'copy';
    
    public static $emptyChoice = array('' => '');

    public static function writeCSRFToken($form){
        if ($form->isCSRFProtected()) echo $form['_csrf_token']->render();
    }

    public static function writeCaptcha(){
        require_once(sfConfig::get('sf_lib_dir') . '/form/recaptchalib.php');
        $publickey = sfConfig::get('app_recaptcha_public_key');
        echo recaptcha_get_html($publickey);
    }

    public static function validateCaptcha(){
        require_once(sfConfig::get('sf_lib_dir') . '/form/recaptchalib.php');
        $privatekey = sfConfig::get('app_recaptcha_private_key');
        $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);

        if (!$resp->is_valid) return false;
        return true;
    }

    public static function writeLabel ($label, $helper = "", $required = false, $rowOpts = array()) {
        echo "<tr ";
        foreach($rowOpts as $key=>$val) {
            echo $key . "='$val'";
        }
        echo "><td valign='top' class='label'>$label" . ($required ? " <span class='required'>*</span>" : "");
        if ($helper) echo "<br><span class='small'>$helper</span>";
        echo "</td>";

    }

    public static function writeField ($field, $form, $opts = array(), $writeError = true) {
        if (!empty($opts['hidden'])) {
            echo $form[$field]->render($opts);
        }
        else {
            echo "<td id='{$field}_widget' class='widget'>{$form[$field]->render($opts)}";
            if ($writeError && $form[$field]->hasError()) {
                echo "<br><span id='{$field}_error' class='error'>{$form[$field]->renderError()}</span>";
            }
            echo"</td></tr>";
        }
    }

    public static function writeError($form, $field, $format = '%s', $forceShell = false){
        if($form[$field]->hasError() || $forceShell){
            echo str_replace("#field#", $field, sprintf($format, $form[$field]->renderError()));
        }
    }

    public static function writeFieldStr($field) {
        echo "<td class='widget'>$field</td>";
    }

    public static function writeButton ($text, $writeError = true) {
        echo "<td class='widget'><button type='submit'>$text</button></td>";
        if ($writeError) echo '<td></td></tr>';
        else echo "</tr>";
    }

    public static function getErrorList($errors) {
        $result = "<ul class='error_list'>";
        foreach ($errors as $error) {
            $result .= "<li>$error</li>";
        }
        $result .= "</ul>";
        return $result;
    }

    public static function getRequestFormValue($request, $parameter) {
        if ($request->hasParameter($parameter)){
            $ret = 'value="' . $request->getParameter($parameter) . '"';
            return $ret;
        }
        return '';
    }




    /*
     * CUSTOM WIDGET AND VALIDATOR CONVIENIENCE FUNCTIONS
     */

    public static function getPhoneValidator($required = true, 
            $requiredMessage = 'Please enter a phone number',
            $invalidMessage = 'Please enter a valid phone number'){
        $messages = self::getMessages($required, $requiredMessage, $invalidMessage);
        return new sfValidatorPhoneNumber(array('required' => $required), $messages);
    }

    public static function getStateWidget($useAbbrev = false){
        if ($useAbbrev){
            return new sfWidgetFormSelect(array('choices' => array('' => '') + Utils::$usStatesAbbrev));
        }
        else
            return new sfWidgetFormSelect(array('choices' => array('' => '') + Utils::$usStates));
    }

    public static function getStateValidator($required = true, 
            $requiredMessage = 'Please select a state',
            $invalidMessage = 'Please select a state'){
        $messages = self::getMessages($required, $requiredMessage, $invalidMessage);
        return new sfValidatorChoice(array('choices' => array_keys(Utils::$usStates), 'required' => $required), $messages);
    }

    public static function getZipValidator($required = true,
            $requiredMessage = 'Please enter a zip code',
            $invalidMessage = 'Please enter a valid zip code'){
        $messages = self::getMessages($required, $requiredMessage, $invalidMessage);
        return new sfValidatorString(array('required' => $required, 'max_length' => 10), $messages);
    }

    
    public static function getEmailValidator($required = true,
            $requiredMessage = 'Please enter an email address.',
            $invalidMessage = 'Please enter a valid email address.'){
        $messages = self::getMessages($required, $requiredMessage, $invalidMessage);
        return new kishkeeValidatorEmail(array("required" => $required), $messages);
    }

    public static function getMessages($required, $requiredMessage, $invalidMessage){
        $options = array();
        if ($required) $options['required'] = $requiredMessage;
        $options['invalid'] = $invalidMessage;
        return $options;
    }

    public static function writeOptions($options, $includeBlank = false){
         if ($includeBlank) $options = array_merge(array(' ' => ' '), $options);
         foreach($options as $key=>$val){
             ?><option value="<?=$key;?>"><?=$val;?></option><?
         }
    }
    public static function writeChecked($val){
        if ($val) echo " checked=\"checked\" ";
    }

}

?>