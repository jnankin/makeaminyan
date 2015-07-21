<?php $options['subject'] = $subject; ?>

<? EmailUtils::emailHeader($options); ?>
<?php echo $first_name; ?>,

<p>An administrator with <?=$minyan->getName();?> has set up an account for you at Make A Minyan!  Now you'll be notified
everytime the minyan needs a minyan via the contact method(s) that you submitted to the administrator.</p>

<p>You can jump right into your account and manage your settings by <a href="http://www.makeaminyan.com/login?user=<?=urlencode($to);?>">clicking here!</a></p>

<p>Here are your current settings: <br/>
    Email: <strong><?=$user->getUsername();?></strong><br/>
    Phone: <strong><?=$user->getPhone();?></strong><br/>
    Temporary Password: <strong><?=sfConfig::get('app_temp_password');?></strong><br/>
    Contact Methods: <strong>
        <? if($minyanUser->getUsePhone()): ?> Phone <? endif; ?>
        <? if($minyanUser->getUseSms()): ?> Text <? endif; ?>
        <? if($minyanUser->getUseEmail()): ?> Email<? endif; ?>
    </strong>


</p>

<p>Don't hesitate to drop us a line or <a href="mailto:support@kishkee.com">send us an email</a> if you need something, we're here to help.</p>


Now go to minyan!<br/>
- The Abishter<br/>

<? EmailUtils::emailFooter($options); ?>