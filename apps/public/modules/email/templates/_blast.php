<?php $options['subject'] = $subject; ?>

<? EmailUtils::emailHeader($options); ?>
<?php echo $blastResponse->getUser()->getFirstName(); ?>,

<h1 align="center">
    <?=$blast->getMinyan()->getName();?> needs you <br /> for a <?=$blast->getEventType();?> minyan <?=$blast->getEventTimeString();?>!
</h1>

<? if ($blast->getExtraReason()): ?><h2 align="center">They really need you because: <?=$blast->getExtraReason();?></h2><? endif; ?>


<table align="center">
    <tr>
        <td style="padding: 20px;">
            <a style="text-align: center; text-decoration: none; cursor: pointer; display: block; width: 200px; height: 110px; padding-top: 40px; display: block; border: 1px solid black; background-color: green; color: white; font-weight: bold; font-size: 16px;"
               href="<?=sfConfig::get('app_url_prefix');?>emailResponse?status=yes&responseId=<?=$blastResponse->getId();?>">
                <span style="font-size: 24px;">Yes</span><br/>
                I can come!
            </a>
        </td>

        <td style="padding: 20px;">
            <a style="text-align: center; text-decoration: none; cursor: pointer; width: 200px; height: 110px; padding-top: 40px; display: block; border: 1px solid black;  background-color: red; color: white; font-weight: bold; font-size: 16px;"
               href="<?=sfConfig::get('app_url_prefix');?>emailResponse?status=no&responseId=<?=$blastResponse->getId();?>">
                <span style="font-size: 24px;">No</span><br/>
                I won't be able to attend
            </a>
        </td>

        <td style="padding: 20px;">
            <a style="text-align: center; text-decoration: none; cursor: pointer; width: 200px; height: 110px; padding-top: 40px; display: block; border: 1px solid black; font-weight: bold; font-size: 16px;"
               href="<?=sfConfig::get('app_url_prefix');?>emailResponse?status=maybe&responseId=<?=$blastResponse->getId();?>">
                <span style="font-size: 24px;">Maybe</span><br/>
                I might be able to come.
            </a>
        </td>
    </tr>
</table>



<? EmailUtils::emailFooter($options); ?>