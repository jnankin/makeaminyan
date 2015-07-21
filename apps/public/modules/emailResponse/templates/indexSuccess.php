<h1>Thank you for your response, <?=$blastResponse->getUser()->getFirstName();?>!</h1><br/><br/>

<h2>You responded that you
<strong>
<? if ($blastResponse->getStatus() == 'yes'): ?><span style="color: green">WOULD</span><? endif; ?>
<? if ($blastResponse->getStatus() == 'no'): ?><span style="color: red">WOULD NOT</span><? endif; ?>
<? if ($blastResponse->getStatus() == 'maybe'): ?><span style="color: black">MIGHT</span><? endif; ?>
</strong>
be able to attend a <?=$eventType;?> minyan at <?=$minyan;?>  <?=$eventTime;?>.
</h2>
<br/><br/>
<? if ($blastResponse->getStatus() != 'yes'): ?>
<h3 align="center">No further action is required at this time.</h3>
<? else: ?>

<?=MAMUtils::writeFlashBlock('additional'); ?>
<h3>If you are bringing others besides yourself, <br/>please enter how many you are bringing here: &nbsp;&nbsp;&nbsp;</h3><br/>
<form action="<?=url_for('emailResponse/index?responseId=' . $blastResponse->getId());?>" method="post">
<input type="text" name="additional" value="<?=$blastResponse->getAdditional();?>" />
<button type="submit">Save</button>
</form>

<? endif; ?>
