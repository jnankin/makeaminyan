<h2>Send new blast for <?=$minyan->getName();?></h2>

<style>
    div.field { margin-bottom: 20px; }
</style>

<script type="text/javascript">
    $().ready(function(){
        var currentDate = new Date();
        var hour = currentDate.getHours();

        if (hour < 12){
            $('#blast_event_type').val('shachris');
        }
        else if (hour >= 12 && hour < 17){
            $('#blast_event_type').val('mincha');
        }
        else if (hour >= 17){
            $('#blast_event_type').val('maariv');
        }
    });
</script>
<form class="tableless" id="blastForm" action="<?php echo url_for('blast/create?minyanId=' . $minyan->getId());?>" method="post">
    <div class="fieldset">
        <div class="fields">
            <div class="field <? if ($form['event_type']->hasError()) echo 'error';?>">
                <?=$form['event_type']->render(); ?>
                <label>Event Type</label>
                <?=$form['event_type']->renderError();?>
            </div>

            <div class="field <? if ($form['event_time']->hasError()) echo 'error';?>">
                <?=$form['event_time']->render(); ?>
                <label>Event Time <span style="color: #CCCCCC"> - leave blank if occurring right now</span></label>
                <?=$form['event_time']->renderError();?>
            </div>

            <div class="field <? if ($form['extra_reason']->hasError()) echo 'error';?>">
                <?=$form['extra_reason']->render(); ?>
                <label>Extra Reason to Come<span style="color: #CCCCCC"> - e.g. Yankel Zissel's father's Yartzheit</span></label>
                <?=$form['extra_reason']->renderError();?>
            </div>
        </div>
    </div>
    <div style="clear: both; "></div>

    <? FormUtils::writeCSRFToken($form);?>
    <div class="fieldset">
        <div class="fields">
            <div class="field">
                <button type="submit" class="action">Send blast!</button>
            </div>
        </div>
    </div>
    <div style="clear: both; "></div>

</form>


