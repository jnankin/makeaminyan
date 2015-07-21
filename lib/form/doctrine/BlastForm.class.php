<?php

/**
 * Blast form.
 *
 * @package    makeaminyan
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BlastForm extends BaseBlastForm {

    public function configure() {
        unset($this['id']);
        unset($this['minyan_id']);
        unset($this['initiating_user_id']);
        unset($this['created_at']);
        unset($this['updated_at']);
        
        unset($this['number_phone_calls']);
        unset($this['number_texts']);
        unset($this['number_emails']);
    }

}
