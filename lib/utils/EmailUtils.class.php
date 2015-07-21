<?

class EmailUtils {

    public static function send($options) {
        $options = array_merge(sfConfig::get('app_mailer_defaults', array()), $options);

        if ($options['send_email']){
            $mailer = sfContext::getInstance()->getMailer();
            $message = Swift_Message::newInstance();

            //get the body
            $body = self::getBody($options);
            $message->setSubject($options['subject']);
            $message->setBody($body);
            $message->addPart($body, 'text/html');

            // Handle other options
            if (isset($options['to'])) {
                $message->setTo(Utils::coerceToArray($options['to']));
            }
            if (isset($options['from'])) {
                $message->setFrom(Utils::coerceToArray($options['from']));
            }
            if (isset($options['bcc'])) {
                $message->setBcc(Utils::coerceToArray($options['bcc']));
            }
            if (isset($options['cc'])) {
                $message->setCc(Utils::coerceToArray($options['cc']));
            }
            if (isset($options['reply-to'])) {
                $message->setReplyTo(Utils::coerceToArray($options['reply-to']));
            }
            if (isset($options['return-path'])) {
                $message->setReturnPath(Utils::coerceToArray($options['return-path']));
            }
            $mailer->send($message);
        }
        else {
            $fakeMessage = " (Email sending prevented by configuration)";
        }

        Utils::logNotice("Sent " . $options['template'] . " email to " . $options['to'] . $fakeMessage );
        
    }

    public static function emailHeader($options) {
        ?>
        <div id="wrapper" style="width: 700px; margin: 0 auto; font-family: 'Trebuchet MS', Arial, sans-serif; font-size: 12px;">
            <p align="right"><img src="http://www.makeaminyan.com/images/logoNoSlogan.png" ></p>
            <div
                style="width: 100%; padding-top: 10px; padding-bottom: 5px; font-size: 18px; border-bottom: 3px solid #FC9D00; margin-bottom: 20px;">
                <?=$options['subject'];?>
            </div>
        <?
    }

    public static function emailFooter($options) {
        ?> <br>
        <br>
        <div id="footer"
             style="border-top: 1px solid #D2D2D2; color: #5F5F5F; font-size: 10px; padding: 10px;">
            This email notification was automatically sent by Make a Minyan. Please do
            not reply to it using your email software.
        </div>
<?
            }

            /**
             * Gets a content from a component, partial, or direct value.
             * Embedded images are replace by their CID
             *
             * $content is either a string or a list of options :
             * - type (partial or component)
             * - name is the name of the partial or component, as "module/partialName" or "module/componentName"
             * - vars is an associative array of variables, passed to the view
             *
             * @param string|array $content
             * @param array $embedded_images
             * @return string
             */
            protected static function getBody($options) {
                if (isset($options['template'])) {
                    self::loadHelper('Partial');
                    $string = get_partial("email/{$options['template']}", $options);
                } else {
                    $string = $options['body'];
                }

                return $string;
            }

            /**
             * Load a helper (uses a wrapper for eventual later change in Symfony's API)
             *
             * @param string $helper
             */
            protected static function loadHelper($helper) {
                if (class_exists('sfProjectConfiguration', true)) {
                    $configuration = sfProjectConfiguration::getActive();

                    return $configuration->loadHelpers(array($helper));
                } else {
                    return sfLoader::loadHelpers(array($helper));
                }
            }

        }

