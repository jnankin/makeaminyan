<?php

class Utils {
    const INT_INFINITY = 2147483647;
    const DOCTRINE_TIMESTAMP_FMT = 'Y-m-d H:i:s';

    public static function clearDiv() {
        echo "<div style='clear: both;'></div>";
    }

    public static function writeIfEqual($a, $b, $out) {
        if ($a == $b)
            echo $out;
    }

    public static function logWarn($message) {
        sfContext::getInstance()->getLogger()->log($message, sfLogger::WARNING);
    }

    public static function logNotice($message) {
        sfContext::getInstance()->getLogger()->log($message, sfLogger::NOTICE);
    }

    public static function logInfo($message) {
        sfContext::getInstance()->getLogger()->log($message, sfLogger::INFO);
    }

    public static function logError($message) {
        sfContext::getInstance()->getLogger()->log($message, sfLogger::ERR);
    }

    public static function logDebug($message) {
        sfContext::getInstance()->getLogger()->log($message, sfLogger::DEBUG);
    }

    public static function moduleHasTemplate($action, $templateName) {
        return file_exists(sfConfig::get('sf_apps_dir') . '/public/modules/' . $action->getModuleName() . '/templates/' . $templateName . 'Success.php');
    }

    public static function stripExtension($fileName) {
        if (strrpos($fileName, '.') === false)
            return $fileName;
        return substr($fileName, 0, strrpos($fileName, '.'));
    }

    public static function incrementFilename($filename) {
        //get filename without extension
        $filename = explode(".", $filename);

        if (count($filename) > 1) {
            $extension = "." . end($filename);
            array_pop($filename);
            $filename = implode(".", $filename);
        } else if (count($filename == 1)) {
            $extension = "";
            $filename = $filename[0];
        } else {
            throw new Exception("Filename $filename is an empty string!");
        }

        $numberFormat = "/^(.*)\(([0-9]+\))$/";
        $numberMatches = array();
        if (preg_match($numberFormat, $filename, $numberMatches)) {
            return $numberMatches[1] . "(" . ($numberMatches[2] + 1) . ")$extension";
        } else {
            return $filename . "(1)$extension";
        }
    }

    public static function requireParam($request, $param, $validFormat = 'string') {
        if (!$request->hasParameter($param)) {
            throw new KishkeeMissingRequiredParamException("Parameter '$param' is required for this action.");
        } else if (!Utils::simpleValidate($request->getParameter($param), $validFormat)) {
            throw new KishkeeMissingRequiredParamException("Parameter '$param' is invalid for this action.");
        }
        return $request->getParameter($param);
    }

    public static function requireParams($request, $params) {
        foreach ($params as $param)
            self::requireParam($request, $param);
    }

    public static function simpleValidate($value, $format) {
        switch ($format) {
            case 'string': return true;
            case 'int': return is_numeric($value);
        }
        return false;
    }

    public static function extractDomainObjectFromRequest($request, $table, $param = null, $doSecurity = false) {
        if ($param == null) {
            $param = $table . "Id";
            $param{0} = strtolower($param{0});
        }

        self::requireParam($request, $param);

        $obj = Doctrine::getTable($table)->find($request->getParameter($param));
        if ($obj) {
            if ($doSecurity && !SecurityManager::verify($obj)) {
                if (sfContext::getInstance()->getUser()->isAuthenticated())
                    throw new Exception("User tried to access a $table that doesnt belong to it! userId=" . sfContext::getInstance()->getUser()->getId());
                else
                    sfContext::getInstance()->getController()->redirect("login/index");
            }
            return $obj;
        }
        else {
            throw new Exception("Could not find $table with id=" . $request->getParameter($param));
        }
    }

    public static function extractSubArrayValues($array, $key) {
        $result = array();
        foreach ($array as $el) {
            array_unshift($result, $el[$key]);
        }
        return $result;
    }

    public static function joinNonEmpty($array, $joinStr = ',') {
        $result = '';
        foreach ($array as $str) {
            if (!self::isEmptyStr($str))
                $result .= $str . $joinStr;
        }
        $result = trim($result, $joinStr);
        return $result;
    }

    public static function joinKeyVal($array, $joinStr = '=') {
        $result = array();
        foreach ($array as $key => $val) {
            $result[] = $key . $joinStr . $val;
        }
        return $result;
    }

    public static function truncateStr($str, $len = 40) {
        if (strlen($str) > $len)
            return substr($str, 0, $len - 3) . '...';
        else
            return $str;
    }

    public static function boolToInt($bool) {
        return ($bool ? 1 : 0);
    }

    public static function safeSimpleStr($str, $stripTags = true, $trim = true, $tolower = false) {
        if ($trim)
            $str = trim($str);
        if ($tolower)
            $str = strtolower($str);

        if ($stripTags)
            $str = strip_tags($str);
        else
            $str = htmlspecialchars($str);

        return $str;
    }

    public static function lcfirst($string) {
        $string{0} = strtolower($string{0});
        return $string;
    }

    public static function ucfirst($string) {
        $string{0} = strtoupper($string{0});
        return $string;
    }

    public static function isAssoc($arr) {
        return array_keys($arr) != range(0, count($arr) - 1);
    }

    public static function coerceToArray($val) {
        if (is_array($val))
            return $val;
        else if ($val != null && $val !== '' && !is_array($val))
            return array($val);
        return null;
    }

    public static function toBoolean($val) {
        if ($val === null || $val === 0 || $val === 'false' || $val === '' || $val === '0' || $val === false)
            return false;
        else
            return true;
    }

    public static function formatPermalink($v) {
        $v = str_replace(" ", "-", $v);
        $v = preg_replace("/[^a-zA-Z0-9_-]/", "", $v);
        $v = strtolower($v);
        $v = trim($v);
        return $v;
    }

    public static function boolToStr($bool) {
        return ($bool ? 'true' : 'false');
    }

    public static function generatePassword($length = 8, $numericOnly = false) {

        // start with a blank password
        $password = "";

        // define possible characters
        if ($numericOnly) {
            $possible = "0123456789";
        } else {
            $possible = "0123456789abcdfghjkmnpqrstvwxyz";
        }

        // set up a counter
        $i = 0;

        // add random characters to $password until $length is reached
        while ($i < $length) {

            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

            // we don't want this character if it's already in the password
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
        }

        // done!
        return $password;
    }

    public static function strToHtmlAscii($string) {
        $result = "";

        if (self::isEmptyStr($string))
            return $result;

        for ($i = 0; $i < strlen($string); $i++) {
            $result .= "&#" . ord(substr($string, $i, 1)) . ";";
        }

        return $result;
    }

    public static function isEmptyStr($str) {
        return strlen(trim($str)) == 0;
    }

    public static function defaultString($str, $default = "") {
        if ($str == null || self::isEmptyStr($str))
            return $default;
        return $str;
    }

    public static function do_post_request($url, $data, $optional_headers = null) {
        $params = array('http' => array('method' => 'post', 'content' => $data));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }

    public static function validURL($url) {
        return true;
    }

    public static function validEmail($email) {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex + 1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                // local part length exceeded
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
                // local part starts or ends with '.'
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = false;
            } else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                            str_replace("\\\\", "", $local))) {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                                str_replace("\\\\", "", $local))) {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }

    public static function validUsername($username) {
        return preg_match('/^[A-Za-z0-9]+$/', $username);
    }

    public static function validPermalink($permalink) {
        return preg_match("/^[A-Za-z0-9-]+$/", $permalink);
    }

    public static function strToNull($str) {
        if ($str == null || $str === '')
            return null;
        else
            return $str;
    }

    public static function urlify($str) {
        if (!self::startsWith($str, 'http://', false))
            return 'http://' . $str;
        else
            return $str;
    }

    public static function startsWith($haystack, $needle, $case=true) {
        if ($case)
            return(substr($haystack, 0, strlen($needle)) === $needle);

        return(strtolower(substr($haystack, 0, strlen($needle))) === strtolower($needle));
    }

    public static function endsWith($haystack, $needle, $case=true) {
        if ($case)
            return (substr($haystack, strlen($haystack) - strlen($needle)) === $needle);


        return (strtolower(substr($haystack, strlen($haystack) - strlen($needle))) === strtolower($needle));
    }

    public static function generateSlug($str) {
        $str = strtolower(trim($str));
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        return $str;
    }

    public static function generateQueryString(sfWebRequest $request, $possibleKeys) {
        $result = "?";
        foreach ($possibleKeys as $key) {
            if ($request->hasParameter($key)) {
                $result .= $key . '=' . $request->getParameter($key) . '&';
            }
        }

        return trim($result, '&');
    }

    public static function ajaxResponse($success, $message = null, $data = null) {
        $response = array(
            'success' => $success,
        );

        if ($message != null)
            $response['message'] = $message;
        if ($data != null)
            $response['data'] = $data;

        return json_encode($response);
    }

    public static function ajaxFormErrorResponse($form) {
        $response = array(
            'success' => false,
            'first' => '',
            'errors' => array()
        );

        $errors = $form->getErrorSchema()->getErrors();
        $first = true;
        foreach ($errors as $key => $error) {
            if ($first) {
                $response['first'] = $error->__toString();
                $first = false;
            }
            $response['errors'][$key] = $error->__toString();
        }

        return json_encode($response);
    }

    public static function javascriptString($str) {
        return "'" . str_replace("'", "\\'", $str) . "'";
    }

    public static function friendlyTimestamp($timestamp) {
        $timestampDay = date('Y-m-d', strtotime($timestamp));

        $dayWord = null;
        if ($timestampDay == date('Y-m-d')) {
            $dayWord = 'Today';
        }
        //less than 48 hours ago
        else if (time() - strtotime($timestampDay) < 172800) {
            $dayWord = 'Yesterday';
        }

        if ($dayWord)
            return $dayWord . ", " . date("g:i a", strtotime($timestamp));
        else
            return date("n/d/Y g:i a", strtotime($timestamp));
    }

    public static function toMysqlDate($date) {
        return date('Y-m-d', strtotime($date));
    }

    public static function formatPhone($phone) {
        if ($phone == null || trim($phone) === '')
            return $phone;

        $phone = preg_replace('/[^0-9]+/', '', $phone);
        $phone = '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
        return $phone;
    }

    public static function getFirst($structure) {
        if (is_array($structure) && count($structure) > 0) {
            return $structure[0];
        }
        else
            return $structure;
    }

    public static function smartDate($date) {
        $sendTime = strtotime($date);
        $timeElapsed = time() - $sendTime;

        $dayInSec = 60 * 60 * 24;
        //one day
        if ($timeElapsed < $dayInSec) {
            return date("g:i a", $sendTime);
        } else if ($timeElapsed < ($dayInSec * 365)) {
            return date("M d", $sendTime);
        }
        else
            return date("m/d/Y", $sendTime);
    }

    public static function allValuesEqual($array) {
        if (!is_array($array)) {
            return true;
        } else if (count($array) == 0) {
            return true;
        } else {
            $first = $array[0];
            foreach ($array as $val) {
                if ($val !== $first)
                    return false;
            }
            return true;
        }
    }

    public static function allNull() {
        $args = func_get_args();
        foreach ($args as $arg) {
            if ($arg !== null)
                return false;
        }
        return true;
    }

    public static function allNullOrZero() {
        $args = func_get_args();
        foreach ($args as $arg) {
            if ($arg !== null && intval($arg) !== 0)
                return false;
        }
        return true;
    }

    public static function formatCents($value) {
        return number_format($value /= 100, 2);
    }

    public static function addParamsToUrl($link, $params) {
        $link = $link . (strstr($link, '?') ? '&' : "?") . self::joinNonEmpty(self::joinKeyVal($params, '='), '&');
        return $link;
    }

    public static function makeRequestSecure($action, $request) {
        if (!$request->isSecure() && sfConfig::get('app_kishkee_hasSSL')) {
            $action->redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        }
    }

    public static function getMonthDropdownChoices() {
        $monthChoices = array();
        for ($i = 1; $i <= 12; $i++) {
            $monthChoices[$i . ''] = date('F', strtotime("$i/1/2010"));
        }
        return $monthChoices;
    }

    /*
      Copyright (c) 2008, reusablecode.blogspot.com; some rights reserved.

      This work is licensed under the Creative Commons Attribution License. To view
      a copy of this license, visit http://creativecommons.org/licenses/by/3.0/ or
      send a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California
      94305, USA.
     */

    // Luhn (mod 10) algorithm

    public static function luhn($input) {
        $sum = 0;
        $odd = strlen($input) % 2;

        // Remove any non-numeric characters.
        if (!is_numeric($input)) {
            eregi_replace("\D", "", $input);
        }

        // Calculate sum of digits.
        for ($i = 0; $i < strlen($input); $i++) {
            $sum += $odd ? $input[$i] : (($input[$i] * 2 > 9) ? $input[$i] * 2 - 9 : $input[$i] * 2);
            $odd = !$odd;
        }

        // Check validity.
        return ($sum % 10 == 0) ? true : false;
    }

    public static function copyDoctrineFields(Doctrine_Record $a, Doctrine_Record $b, array $fields) {
        foreach ($fields as $field) {
            $b->set($field, $a->get($field));
        }
    }

    public static $usStates = array('AL' => "Alabama",
        'AK' => "Alaska",
        'AZ' => "Arizona",
        'AR' => "Arkansas",
        'CA' => "California",
        'CO' => "Colorado",
        'CT' => "Connecticut",
        'DE' => "Delaware",
        'DC' => "District Of Columbia",
        'FL' => "Florida",
        'GA' => "Georgia",
        'HI' => "Hawaii",
        'ID' => "Idaho",
        'IL' => "Illinois",
        'IN' => "Indiana",
        'IA' => "Iowa",
        'KS' => "Kansas",
        'KY' => "Kentucky",
        'LA' => "Louisiana",
        'ME' => "Maine",
        'MD' => "Maryland",
        'MA' => "Massachusetts",
        'MI' => "Michigan",
        'MN' => "Minnesota",
        'MS' => "Mississippi",
        'MO' => "Missouri",
        'MT' => "Montana",
        'NE' => "Nebraska",
        'NV' => "Nevada",
        'NH' => "New Hampshire",
        'NJ' => "New Jersey",
        'NM' => "New Mexico",
        'NY' => "New York",
        'NC' => "North Carolina",
        'ND' => "North Dakota",
        'OH' => "Ohio",
        'OK' => "Oklahoma",
        'OR' => "Oregon",
        'PA' => "Pennsylvania",
        'RI' => "Rhode Island",
        'SC' => "South Carolina",
        'SD' => "South Dakota",
        'TN' => "Tennessee",
        'TX' => "Texas",
        'UT' => "Utah",
        'VT' => "Vermont",
        'VA' => "Virginia",
        'WA' => "Washington",
        'WV' => "West Virginia",
        'WI' => "Wisconsin",
        'WY' => "Wyoming");

    public static $usStatesAbbrev = array('AL' => "AL",
        'AK' => "AK",
        'AZ' => "AZ",
        'AR' => "AR",
        'CA' => "CA",
        'CO' => "CO",
        'CT' => "CT",
        'DE' => "DE",
        'DC' => "DC",
        'FL' => "FL",
        'GA' => "GA",
        'HI' => "HI",
        'ID' => "ID",
        'IL' => "IL",
        'IN' => "IN",
        'IA' => "IA",
        'KS' => "KS",
        'KY' => "KY",
        'LA' => "LA",
        'ME' => "ME",
        'MD' => "MD",
        'MA' => "MA",
        'MI' => "MI",
        'MN' => "MN",
        'MS' => "MS",
        'MO' => "MO",
        'MT' => "MT",
        'NE' => "NE",
        'NV' => "NV",
        'NH' => "NH",
        'NJ' => "NJ",
        'NM' => "NM",
        'NY' => "NY",
        'NC' => "NC",
        'ND' => "ND",
        'OH' => "OH",
        'OK' => "OK",
        'OR' => "OR",
        'PA' => "PA",
        'RI' => "RI",
        'SC' => "SC",
        'SD' => "SD",
        'TN' => "TN",
        'TX' => "TX",
        'UT' => "UT",
        'VT' => "VT",
        'VA' => "VA",
        'WA' => "WA",
        'WV' => "WV",
        'WI' => "WI",
        'WY' => "WY");

}
