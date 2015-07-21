<?php $options['subject'] = $subject; ?>

<? EmailUtils::emailHeader($options); ?>
<?php echo $first_name; ?>,

<p>Congratulations, your Make a Minyan account has been set up successfully!</p>
<p>You can jump right into your account and subscribe to a minyan by <a href="http://www.makeaminyan.com/login?user=<?=urlencode($to);?>">clicking here!</a></p>

<p>Just in case you forget your password, you can get a new one by <a href="http://www.makeaminyan.com/forgotPassword?user=<?=urlencode($to);?>">clicking here.</a></p>

<p>Don't hesitate to drop us a line or <a href="mailto:support@kishkee.com">send us an email</a> if you need something, we're here to help.</p>


Now go to minyan!<br/>

- The Abishter<br/>

<? EmailUtils::emailFooter($options); ?>